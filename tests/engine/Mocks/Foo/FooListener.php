<?php

namespace Minetro\Tests\Mocks\Foo;

use Minetro\Nextras\Events\Listeners\BeforePersistListener;
use Nextras\Orm\Entity\IEntity;

final class FooListener implements BeforePersistListener
{

    /** @var array */
    public $onCall = [];

    /** @var string */
    public $onCallHistory = [];

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onBeforePersist(IEntity $entity)
    {
        $method = str_replace(__CLASS__ . '::', NULL, __METHOD__);
        foreach ($this->onCall as $cb) {
            $cb($method, $entity);
        }
        $this->onCallHistory[] = $method;
    }

}
