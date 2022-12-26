<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\InvalidFoo\Foo;

use Nextras\Orm\Repository\Repository;

final class InvalidFooRepository extends Repository
{

	/**
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [InvalidFoo::class];
	}

}
