<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\Foo\Foo;

use Nextras\Orm\Repository\Repository;

final class FooRepository extends Repository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [Foo::class];
	}

}
