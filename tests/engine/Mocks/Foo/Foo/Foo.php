<?php

namespace Minetro\Tests\Mocks\Foo\Foo;

use Nextras\Orm\Entity\Entity;

/**
 * @property int $id        {primary}
 * @property string $bar
 *
 * @BeforePersist(Minetro\Tests\Mocks\Foo\FooListener)
 * @Lifecycle(Minetro\Tests\Mocks\Foo\FooLifecycleListener)
 */
final class Foo extends Entity
{

}
