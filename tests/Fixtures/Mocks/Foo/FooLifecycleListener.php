<?php declare(strict_types = 1);

namespace Tests\Fixtures\Mocks\Foo;

use Contributte\Nextras\Orm\Events\Listeners\LifecycleListener;
use Nextras\Orm\Entity\IEntity;

final class FooLifecycleListener implements LifecycleListener
{

	/** @var callable[] */
	public array $onCall = [];

	/** @var string[] */
	public array $onCallHistory = [];

	public function onAfterInsert(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	public function onAfterPersist(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	public function onAfterRemove(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	public function onAfterUpdate(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	public function onBeforeInsert(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	public function onBeforePersist(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	public function onBeforeRemove(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	public function onBeforeUpdate(IEntity $entity): void
	{
		$this->call(__METHOD__, $entity);
	}

	/**
	 * @param IEntity[] $persisted
	 * @param IEntity[] $removed
	 */
	public function onFlush(array $persisted, array $removed): void
	{
		// Not implemented
	}

	public function call(string $method, IEntity $entity): void
	{
		$method = str_replace(self::class . '::', '', $method);
		foreach ($this->onCall as $cb) {
			$cb($method, $entity);
		}

		$this->onCallHistory[] = $method;
	}

}
