<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Nextras\Orm\Events\DI\NextrasOrmEventsExtension;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\DI\ServiceCreationException;
use Nextras\Dbal\Bridges\NetteDI\DbalExtension;
use Nextras\Orm\Bridges\NetteDI\OrmExtension;
use Tester\Assert;
use Tester\FileMock;
use Tester\TestCase;
use Tests\Fixtures\Mocks\Foo\Foo\Foo;
use Tests\Fixtures\Mocks\Foo\Foo\FooRepository;
use Tests\Fixtures\Mocks\Foo\FooLifecycleListener;
use Tests\Fixtures\Mocks\Foo\FooListener;
use Tests\Fixtures\Mocks\Foo\Model;
use Tests\Fixtures\Mocks\InvalidFoo\InvalidModel;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class EventsTest extends TestCase
{

	protected function createContainer(callable $callback): Container
	{
		$loader = new ContainerLoader(TEMP_DIR, true);
		$class = $loader->load(function (Compiler $compiler) use ($callback): void {
			$compiler->addExtension('orm', new OrmExtension());
			$compiler->addExtension('dbal', new DbalExtension());
			$compiler->addExtension('orm.events', new NextrasOrmEventsExtension());

			$compiler->addConfig([
				'orm' => [
					'model' => Model::class,
				],
			]);

			$compiler->loadConfig(FileMock::create('
			services:
				cache: Nette\Caching\Storages\DevNullStorage
				
				- Tests\Fixtures\Mocks\Foo\FooListener 
				- Tests\Fixtures\Mocks\Foo\FooLifecycleListener
		', 'neon'));

			$callback($compiler);
		}, md5(microtime() . mt_rand(1, 1000)));

		return new $class();
	}

	protected function createSimpleContainer(): Container
	{
		return $this->createContainer(function (): void {
		});
	}

	public function testListenerLazyLoading(): void
	{
		$container = $this->createSimpleContainer();
		$repository = $container->getByType(FooRepository::class);

		Assert::falsey($container->isCreated($container->findByType(FooListener::class)[0]));
		Assert::falsey($container->isCreated($container->findByType(FooLifecycleListener::class)[0]));

		$entity = new Foo();
		$entity->bar = 'foobar';

		$repository->persistAndFlush($entity);

		Assert::truthy($container->isCreated($container->findByType(FooListener::class)[0]));
		Assert::truthy($container->isCreated($container->findByType(FooLifecycleListener::class)[0]));
	}

	public function testEventsHierarchy(): void
	{
		$container = $this->createSimpleContainer();

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
	}

	public function testInvalidListener(): void
	{
		Assert::throws(
			function (): void {
				$this->createContainer(function (Compiler $compiler): void {
					$compiler->addConfig([
						'orm' => [
							'model' => InvalidModel::class,
						],
					]);

					$compiler->loadConfig(FileMock::create('
					services:
						cache: Nette\Caching\Storages\DevNullStorage
						
						- Tests\Fixtures\Mocks\InvalidFoo\BadListener
				', 'neon'));
				});
			},
			ServiceCreationException::class,
			"Object 'Tests\Fixtures\Mocks\InvalidFoo\BadListener' should implement 'Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener'"
		);
	}

}

(new EventsTest())->run();
