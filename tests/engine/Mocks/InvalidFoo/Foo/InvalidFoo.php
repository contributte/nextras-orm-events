<?php

namespace Minetro\Tests\Mocks\InvalidFoo\Foo;

use Nextras\Orm\Entity\Entity;

/**
 * @property int $id        {primary}
 * @property string $bar
 *
 * @BeforePersist(Minetro\Tests\Mocks\InvalidFoo\BadListener)
 */
final class InvalidFoo extends Entity
{

}
