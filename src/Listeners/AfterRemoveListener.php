<?php

namespace Minetro\Nextras\Events\Listeners;

use Nextras\Orm\Entity\IEntity;

interface AfterRemoveListener
{

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onAfterRemove(IEntity $entity);

}
