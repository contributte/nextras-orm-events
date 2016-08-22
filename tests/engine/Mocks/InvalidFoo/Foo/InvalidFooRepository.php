<?php

namespace Minetro\Tests\Mocks\InvalidFoo\Foo;

use Nextras\Orm\Repository\Repository;

final class InvalidFooRepository extends Repository
{

    /**
     * @return string[]
     */
    public static function getEntityClassNames()
    {
        return [InvalidFoo::class];
    }
}