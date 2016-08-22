<?php

namespace Minetro\Tests\Mocks\Foo;

use Nextras\Orm\Entity\Entity;

/**
 * @property int $id        {primary}
 * @property string $bar
 *
 * @BeforePersist(Minetro\Tests\Mocks\FooListener)
 * @Lifecycle(Minetro\Tests\Mocks\FooLifecycleListener)
 */
final class Foo extends Entity
{

}
