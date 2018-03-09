<?php

namespace Tests\Fixtures\Mocks\Foo;

use Nextras\Orm\Model\Model as NextrasModel;
use Tests\Fixtures\Mocks\Foo\Foo\FooRepository;

/**
 * @property-read FooRepository $foo
 */
final class Model extends NextrasModel
{

}
