<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\InvalidFoo;

use Nextras\Orm\Model\Model as NextrasModel;
use Tests\Fixtures\Mocks\InvalidFoo\Foo\InvalidFooRepository;

/**
 * @property-read InvalidFooRepository $foo
 */
final class InvalidModel extends NextrasModel
{

}
