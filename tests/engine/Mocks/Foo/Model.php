<?php

namespace Minetro\Tests\Mocks\Foo;

use Minetro\Tests\Mocks\Foo\Foo\FooRepository;
use Nextras\Orm\Model\Model as NextrasModel;

/**
 * @property-read FooRepository $foo
 */
final class Model extends NextrasModel
{

}
