<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\Foo;

use Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener;
use Nextras\Orm\Entity\IEntity;

final class FooListener implements BeforePersistListener
{

	/** @var callable[] */
	public $onCall = [];

	/** @var string[] */
	public $onCallHistory = [];

	public function onBeforePersist(IEntity $entity): void
	{
		$method = str_replace(self::class . '::', '', __METHOD__);
		foreach ($this->onCall as $cb) {
			$cb($method, $entity);
		}

		$this->onCallHistory[] = $method;
	}

}
