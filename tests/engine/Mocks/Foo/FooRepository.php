<?php

namespace Minetro\Tests\Mocks\Foo;

use Nextras\Orm\Repository\Repository;

final class FooRepository extends Repository
{

    /**
     * @return string[]
     */
    public static function getEntityClassNames()
    {
        return [Foo::class];
    }
}