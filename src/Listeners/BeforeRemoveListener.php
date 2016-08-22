<?php

namespace Minetro\Nextras\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface BeforeRemoveListener
{

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onBeforeRemove(IEntity $entity);

}
