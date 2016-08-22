<?php

namespace Minetro\Tests\Mocks\InvalidFoo\Foo;

use Nette\Caching\Cache;
use Nextras\Orm\Mapper\Memory\ArrayMapper;

final class InvalidFooMapper extends ArrayMapper
{

    /** @var mixed */
    private $_data;

    /** @var Cache */
    private $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    protected function readData()
    {
        return $this->_data;
    }

    /**
     * @param array $data
     */
    protected function saveData(array $data)
    {
        $this->_data = $data;
    }

}
