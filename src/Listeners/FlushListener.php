<?php declare(strict_types = 1);

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface FlushListener
{

	/**
	 * @param IEntity[] $persisted
	 * @param IEntity[] $removed
	 */
	public function onFlush(array $persisted, array $removed): void;

}
