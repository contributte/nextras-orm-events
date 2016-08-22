# Nextras\ORM Events

Doctrine-like events for Nextras\ORM Entity.

-----

[![Build Status](https://img.shields.io/travis/minetro/nextras-orm-events.svg?style=flat-square)](https://travis-ci.org/minetro/nextras-orm-events)
[![Code coverage](https://img.shields.io/coveralls/minetro/nextras-orm-events.svg?style=flat-square)](https://coveralls.io/r/minetro/nextras-orm-events)
[![Downloads this Month](https://img.shields.io/packagist/dm/minetro/nextras-orm-events.svg?style=flat-square)](https://packagist.org/packages/minetro/nextras-orm-events)
[![Downloads total](https://img.shields.io/packagist/dt/minetro/nextras-orm-events.svg?style=flat-square)](https://packagist.org/packages/minetro/nextras-orm-events)
[![Latest stable](https://img.shields.io/packagist/v/minetro/nextras-orm-events.svg?style=flat-square)](https://packagist.org/packages/minetro/nextras-orm-events)
[![HHVM Status](https://img.shields.io/hhvm/minetro/nextras-orm-events.svg?style=flat-square)](http://hhvm.h4cc.de/package/minetro/nextras-orm-events)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/minetro/nette.svg?style=flat-square)](https://gitter.im/minetro/nette?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Install

```sh
composer require minetro/nextras-orm-events
```

## Usage

### Entity

Just add annotation `@<Before/Update>` to your entity.

```php
/**
 * @BeforeInsert(My/BeforeInsertListener)
 * @BeforePersist(My/BeforePersistListener)
 * @BeforeRemove(My/BeforeRemoveListener)
 * @BeforeUpdate(My/BeforeUpdateListener)
 * @AfterInsert(My/AfterInsertListener)
 * @AfterPersist(My/AfterPersistListener)
 * @AfterRemove(My/AfterRemoveListener)
 * @AfterUpdate(My/AfterUpdateListener)
 
 * @Lifecycle(My/LifecycleListener)
 */
class Foo extends Entity
{
}

```

### Service

```php

namespace My;

use Minetro\Nextras\Events\Listeners\BeforePersistListener;

final class BeforePersistListener implements BeforePersistListener
{

    /**
     * @param IEntity $entity
     * @return void
     */
    public function onBeforePersist(IEntity $entity)
    {
        // ...
    }

}
```

### Config

```yaml
services:
    - My\BeforePersistListener
```

That's all. Super ultra simple.

-----

Thanks for testing, reporting and contributing.
