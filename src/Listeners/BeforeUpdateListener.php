<?php

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface BeforeUpdateListener
{

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function onBeforeUpdate(IEntity $entity);

}
