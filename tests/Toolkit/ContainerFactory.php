<?php declare(strict_types = 1);

namespace Tests\Toolkit;

use Contributte\Nextras\Orm\Events\DI\NextrasOrmEventsExtension;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Nette\DI\Compiler;
use Nextras\Orm\Bridges\NetteDI\OrmExtension;

final class ContainerFactory
{

	public static function create(): ContainerBuilder
	{
		return ContainerBuilder::of()
			->withCompiler(function (Compiler $compiler): void {
				$compiler->addExtension('orm', new OrmExtension());
				$compiler->addExtension('orm.events', new NextrasOrmEventsExtension());

				$compiler->addConfig(Neonkit::load(<<<'NEON'
					orm:
						model: Tests\Fixtures\Mocks\Foo\Model

					services:
						cache: Nette\Caching\Storages\DevNullStorage
						orm.mapperCoordinator: stdClass

						- Tests\Fixtures\Mocks\Foo\FooListener
						- Tests\Fixtures\Mocks\Foo\FooLifecycleListener
						- Tests\Fixtures\Mocks\Foo\FooTraitListener
				NEON
				));
			});
	}

}
