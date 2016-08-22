<?php

namespace Minetro\Tests\Mocks\InvalidFoo;

use Minetro\Tests\Mocks\InvalidFoo\Foo\InvalidFooRepository;
use Nextras\Orm\Model\Model as NextrasModel;

/**
 * @property-read InvalidFooRepository $foo
 */
final class InvalidModel extends NextrasModel
{

}
