<?php

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface AfterPersistListener
{

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function onAfterPersist(IEntity $entity);

}
