<?php

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface BeforePersistListener
{

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function onBeforePersist(IEntity $entity);

}
