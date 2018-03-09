<?php

namespace Tests\Fixtures\Mocks\Foo\Foo;

use Nette\Caching\Cache;
use Nextras\Orm\Mapper\Memory\ArrayMapper;

final class FooMapper extends ArrayMapper
{

	/** @var mixed */
	private $_data;

	/**
	 * @param Cache $cache
	 */
	public function __construct(Cache $cache)
	{
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
	 * @return void
	 */
	protected function saveData(array $data)
	{
		$this->_data = $data;
	}

}
