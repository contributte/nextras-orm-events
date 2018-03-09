<?php

namespace Tests\Fixtures\Mocks\InvalidFoo\Foo;

use Nextras\Orm\Mapper\Memory\ArrayMapper;

final class InvalidFooMapper extends ArrayMapper
{

	/** @var mixed */
	private $_data;

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
