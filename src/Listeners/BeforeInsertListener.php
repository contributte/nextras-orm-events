<?php

namespace Contributte\Nextras\Orm\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface BeforeInsertListener
{

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function onBeforeInsert(IEntity $entity);

}
