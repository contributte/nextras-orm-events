<?php

namespace Minetro\Tests\Mocks\Foo;

use Minetro\Nextras\Events\Listeners\LifecycleListener;
use Nextras\Orm\Entity\IEntity;

final class FooLifecycleListener implements LifecycleListener
{

    /** @var array */
    public $onCall = [];

    /** @var string */
    public $onCallHistory = [];

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onAfterInsert(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onAfterPersist(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onAfterRemove(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onAfterUpdate(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onBeforeInsert(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onBeforePersist(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onBeforeRemove(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onBeforeUpdate(IEntity $entity)
    {
        $this->call(__METHOD__, $entity);
    }

    /**
     * HELPERS *****************************************************************
     */

    /**
     * @param string $method
     * @param object $entity
     */
    public function call($method, $entity)
    {
        $method = str_replace(__CLASS__ . '::', NULL, $method);
        foreach ($this->onCall as $cb) {
            $cb($method, $entity);
        }
        $this->onCallHistory[] = $method;
    }
}