<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\Foo\Foo;

use Nextras\Orm\Entity\Entity;

/**
 * @property int    $id  {primary}
 * @property string $bar
 * @BeforePersist(Tests\Fixtures\Mocks\Foo\FooListener)
 * @Lifecycle(Tests\Fixtures\Mocks\Foo\FooLifecycleListener)
 */
final class Foo extends Entity
{

	use FooTrait;

}
