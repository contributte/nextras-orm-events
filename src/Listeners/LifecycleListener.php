<?php declare(strict_types = 1);

namespace Contributte\Nextras\Orm\Events\Listeners;

interface LifecycleListener extends
	BeforeInsertListener,
	BeforePersistListener,
	BeforeRemoveListener,
	BeforeUpdateListener,
	AfterInsertListener,
	AfterPersistListener,
	AfterRemoveListener,
	AfterUpdateListener,
	FlushListener
{

}
