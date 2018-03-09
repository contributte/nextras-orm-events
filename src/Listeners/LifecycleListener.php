<?php

namespace Contributte\Nextras\Orm\Events\Listeners;

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
