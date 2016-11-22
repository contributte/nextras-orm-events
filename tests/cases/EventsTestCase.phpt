<?php

/**
 * @Test Minetro\Nextras\Events\DI\NextrasEventsExtension
 */

use Minetro\Nextras\Events\DI\NextrasEventsExtension;
use Minetro\Tests\Mocks\Foo\Foo\Foo;
use Minetro\Tests\Mocks\Foo\Foo\FooRepository;
use Minetro\Tests\Mocks\Foo\FooLifecycleListener;
use Minetro\Tests\Mocks\Foo\FooListener;
use Minetro\Tests\Mocks\Foo\Model;
use Minetro\Tests\Mocks\InvalidFoo\InvalidModel;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\DI\ServiceCreationException;
use Nextras\Dbal\Bridges\NetteDI\DbalExtension;
use Nextras\Orm\Bridges\NetteDI\OrmExtension;
use Tester\Assert;
use Tester\FileMock;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

final class EventsTestCase extends TestCase
{

    /**
     * @return Container
     */
    protected function createContainer(callable $callback)
    {
        $loader = new ContainerLoader(TEMP_DIR, TRUE);
        $class = $loader->load(function (Compiler $compiler) use ($callback) {
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
                
                - Minetro\Tests\Mocks\Foo\FooListener 
                - Minetro\Tests\Mocks\Foo\FooLifecycleListener
        ', 'neon'));

            $callback($compiler);
        }, md5(microtime() . mt_rand(1, 1000)));

        return new $class;
    }

    /**
     * @return Container
     */
    protected function createSimpleContainer()
    {
        return $this->createContainer(function () {
        });
    }

    public function testEventsHierarchy()
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

    public function testInvalidListener()
    {
        Assert::throws(function () {
            $this->createContainer(function (Compiler $compiler) {
                $compiler->addConfig([
                    'orm' => [
                        'model' => InvalidModel::class,
                    ],
                ]);

                $compiler->loadConfig(FileMock::create('
                    services:
                        cache: Nette\Caching\Storages\DevNullStorage
                        
                        - Minetro\Tests\Mocks\InvalidFoo\BadListener
                ', 'neon'));
            });
        },
            ServiceCreationException::class,
            "Object 'Minetro\Tests\Mocks\InvalidFoo\BadListener' should implement 'Minetro\Nextras\Events\Listeners\BeforePersistListener'"
        );
    }

}

(new EventsTestCase())->run();
