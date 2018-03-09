<?php

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface AfterInsertListener
{

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function onAfterInsert(IEntity $entity);

}
