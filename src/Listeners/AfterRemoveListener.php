<?php declare(strict_types = 1);

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface AfterRemoveListener
{

	public function onAfterRemove(IEntity $entity): void;

}
