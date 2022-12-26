<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\InvalidFoo\Foo;

use Nextras\Orm\Mapper\Memory\ArrayMapper;

final class InvalidFooMapper extends ArrayMapper
{

	/** @var mixed[] */
	private $_data;

	/**
	 * @return mixed[]
	 */
	protected function readData(): array
	{
		return $this->_data;
	}

	/**
	 * @param mixed[] $data
	 */
	protected function saveData(array $data): void
	{
		$this->_data = $data;
	}

}
