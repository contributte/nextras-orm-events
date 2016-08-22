<?php

namespace Minetro\Tests\Mocks;

use Minetro\Tests\Mocks\Foo\FooRepository;
use Nextras\Orm\Model\Model as NextrasModel;

/**
 * @property-read FooRepository $foo
 */
final class Model extends NextrasModel
{

}
