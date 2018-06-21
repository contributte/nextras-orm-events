<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\Foo\Foo;

use Nette\Caching\Cache;
use Nextras\Orm\Mapper\Memory\ArrayMapper;

final class FooMapper extends ArrayMapper
{

	/** @var mixed[]| */
	private $_data = [];

	public function __construct(Cache $cache)
	{
	}

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
