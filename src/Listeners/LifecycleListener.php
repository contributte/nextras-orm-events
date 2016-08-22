<?php

namespace Minetro\Nextras\Events\Listeners;

interface LifecycleListener extends
    BeforeInsertListener,
    BeforePersistListener,
    BeforeRemoveListener,
    BeforeUpdateListener,
    AfterInsertListener,
    AfterPersistListener,
    AfterRemoveListener,
    AfterUpdateListener
{

}
