<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\Neonkit;
use Nette\DI\Compiler;
use Nette\DI\ServiceCreationException;
use Tester\Assert;
use Tests\Fixtures\Mocks\Foo\Foo\Foo;
use Tests\Fixtures\Mocks\Foo\Foo\FooRepository;
use Tests\Fixtures\Mocks\Foo\FooLifecycleListener;
use Tests\Fixtures\Mocks\Foo\FooListener;
use Tests\Fixtures\Mocks\Foo\FooTraitListener;
use Tests\Toolkit\ContainerFactory;

require_once __DIR__ . '/../bootstrap.php';

Toolkit::test(function (): void {
	$container = ContainerFactory::create()->build();
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
});

Toolkit::test(function (): void {
	$container = ContainerFactory::create()->build();

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
});

Toolkit::test(function (): void {
	Assert::throws(
		function (): void {
			ContainerFactory::create()
				->withCompiler(function (Compiler $compiler): void {
					$compiler->addConfig(Neonkit::load(<<<'NEON'
						orm:
							model: Tests\Fixtures\Mocks\InvalidFoo\InvalidModel

						services:
							cache: Nette\Caching\Storages\DevNullStorage
							orm.mapperCoordinator: stdClass
							- Tests\Fixtures\Mocks\InvalidFoo\BadListener
					NEON
					));
				})
				->build();
		},
		ServiceCreationException::class,
		"Object 'Tests\Fixtures\Mocks\InvalidFoo\BadListener' should implement 'Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener'"
	);
});
