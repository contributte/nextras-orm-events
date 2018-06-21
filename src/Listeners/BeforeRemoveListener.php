<?php declare(strict_types = 1);

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface BeforeRemoveListener
{

	public function onBeforeRemove(IEntity $entity): void;

}
