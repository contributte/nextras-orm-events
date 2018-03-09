<?php

namespace Tests\Fixtures\Mocks\Foo;

use Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener;
use Nextras\Orm\Entity\IEntity;

final class FooListener implements BeforePersistListener
{

	/** @var array */
	public $onCall = [];

	/** @var string */
	public $onCallHistory = [];

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function onBeforePersist(IEntity $entity)
	{
		$method = str_replace(__CLASS__ . '::', NULL, __METHOD__);
		foreach ($this->onCall as $cb) {
			$cb($method, $entity);
		}
		$this->onCallHistory[] = $method;
	}

}
