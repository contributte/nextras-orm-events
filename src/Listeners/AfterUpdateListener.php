<?php

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface AfterUpdateListener
{

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function onAfterUpdate(IEntity $entity);

}
