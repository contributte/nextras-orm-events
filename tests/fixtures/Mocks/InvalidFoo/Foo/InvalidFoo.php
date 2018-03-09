<?php

namespace Tests\Fixtures\Mocks\InvalidFoo\Foo;

use Nextras\Orm\Entity\Entity;

/**
 * @property int $id        {primary}
 * @property string $bar
 *
 * @BeforePersist(Tests\Fixtures\Mocks\InvalidFoo\BadListener)
 */
final class InvalidFoo extends Entity
{

}
