<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Nextras\Orm\Events\DI\NextrasOrmEventsExtension;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Nette\DI\Compiler;
use Nette\DI\ServiceCreationException;
use Nextras\Dbal\Bridges\NetteDI\DbalExtension;
use Nextras\Orm\Bridges\NetteDI\OrmExtension;
use Tester\Assert;
use Tester\TestCase;
use Tests\Fixtures\Mocks\Foo\Foo\Foo;
use Tests\Fixtures\Mocks\Foo\Foo\FooRepository;
use Tests\Fixtures\Mocks\Foo\FooLifecycleListener;
use Tests\Fixtures\Mocks\Foo\FooListener;
use Tests\Fixtures\Mocks\Foo\FooTraitListener;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class EventsTest extends TestCase
{

	public function testListenerLazyLoading(): void
	{
		$container = $this->createContainerBuilder()->build();
		$repository = $container->getByType(FooRepository::class);

		Assert::falsey($container->isCreated($container->findByType(FooListener::class)[0]));
		Assert::falsey($container->isCreated($container->findByType(FooLifecycleListener::class)[0]));
		Assert::falsey($container->isCreated($container->findByType(FooTraitListener::class)[0]));

		$entity = new Foo();
		$entity->bar = 'foobar';

		$repository->persistAndFlush($entity);

		Assert::truthy($container->isCreated($container->findByType(FooListener::class)[0]));
		Assert::truthy($container->isCreated($container->findByType(FooLifecycleListener::class)[0]));
		Assert::truthy($container->isCreated($container->findByType(FooTraitListener::class)[0]));
	}

	public function testEventsHierarchy(): void
	{
		$container = $this->createContainerBuilder()->build();

		/** @var FooRepository $repository */
		$repository = $container->getByType(FooRepository::class);

		$entity = new Foo();
		$entity->bar = 'foobar';

		$repository->persistAndFlush($entity);

		/** @var FooLifecycleListener $listener */
		$listener = $container->getByType(FooLifecycleListener::class);
		Assert::equal([
			'onBeforePersist',
			'onBeforeInsert',
			'onAfterInsert',
			'onAfterPersist',
		], $listener->onCallHistory);

		/** @var FooListener $listener */
		$listener = $container->getByType(FooListener::class);
		Assert::equal(['onBeforePersist'], $listener->onCallHistory);

		/** @var FooTraitListener $listener */
		$listener = $container->getByType(FooTraitListener::class);
		Assert::equal(['onBeforePersist'], $listener->onCallHistory);
	}

	public function testInvalidListener(): void
	{
		Assert::throws(
			function (): void {
				$this->createContainerBuilder()
					->withCompiler(function (Compiler $compiler): void {
						$compiler->addConfig(Neonkit::load(<<<'NEON'
							orm:
								model: Tests\Fixtures\Mocks\InvalidFoo\InvalidModel

							services:
								cache: Nette\Caching\Storages\DevNullStorage
								- Tests\Fixtures\Mocks\InvalidFoo\BadListener
						NEON
						));
					})
					->build();
			},
			ServiceCreationException::class,
			"Object 'Tests\Fixtures\Mocks\InvalidFoo\BadListener' should implement 'Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener'"
		);
	}

	private function createContainerBuilder(): ContainerBuilder
	{
		return ContainerBuilder::of()
			->withCompiler(function (Compiler $compiler): void {
				$compiler->addExtension('orm', new OrmExtension());
				$compiler->addExtension('dbal', new DbalExtension());
				$compiler->addExtension('orm.events', new NextrasOrmEventsExtension());

				$compiler->addConfig(Neonkit::load(<<<'NEON'
					orm:
						model: Tests\Fixtures\Mocks\Foo\Model

					services:
						cache: Nette\Caching\Storages\DevNullStorage

						- Tests\Fixtures\Mocks\Foo\FooListener
						- Tests\Fixtures\Mocks\Foo\FooLifecycleListener
						- Tests\Fixtures\Mocks\Foo\FooTraitListener
				NEON
				));
			});
	}

}

(new EventsTest())->run();
