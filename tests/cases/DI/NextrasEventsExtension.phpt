<?php

/**
 * @Test Minetro\Nextras\Events\DI\NextrasEventsExtension
 */

use Minetro\Nextras\Events\DI\NextrasEventsExtension;
use Minetro\Tests\Mocks\Foo\Foo;
use Minetro\Tests\Mocks\Foo\FooRepository;
use Minetro\Tests\Mocks\FooLifecycleListener;
use Minetro\Tests\Mocks\FooListener;
use Minetro\Tests\Mocks\Model;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nextras\Dbal\Bridges\NetteDI\DbalExtension;
use Nextras\Orm\Bridges\NetteDI\OrmExtension;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

test(function () {
    $loader = new ContainerLoader(TEMP_DIR, TRUE);
    $class = $loader->load(function (Compiler $compiler) {
        $compiler->addExtension('orm', new OrmExtension());
        $compiler->addExtension('dbal', new DbalExtension());
        $compiler->addExtension('orm.events', new NextrasEventsExtension());

        $compiler->addConfig([
            'orm' => [
                'model' => Model::class,
            ],
        ]);

        $compiler->loadConfig(FileMock::create('
            services:
                cache: Nette\Caching\Storages\DevNullStorage
                
                - Minetro\Tests\Mocks\FooListener 
                - Minetro\Tests\Mocks\FooLifecycleListener
        ', 'neon'));
    }, time());

    /** @var Container $container */
    $container = new $class;

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
});
