# Nextras ORM Events

Doctrine-like events for Nextras ORM entity lifecycle.

-----

[![Build Status](https://img.shields.io/travis/contributte/nextras-orm-events.svg?style=flat-square)](https://travis-ci.org/contributte/nextras-orm-events)
[![Code coverage](https://img.shields.io/coveralls/contributte/nextras-orm-events.svg?style=flat-square)](https://coveralls.io/r/contributte/nextras-orm-events)
[![Licence](https://img.shields.io/packagist/l/contributte/nextras-orm-events.svg?style=flat-square)](https://packagist.org/packages/contributte/nextras-orm-events)

[![Downloads this Month](https://img.shields.io/packagist/dm/contributte/nextras-orm-events.svg?style=flat-square)](https://packagist.org/packages/contributte/nextras-orm-events)
[![Downloads total](https://img.shields.io/packagist/dt/contributte/nextras-orm-events.svg?style=flat-square)](https://packagist.org/packages/contributte/nextras-orm-events)
[![Latest stable](https://img.shields.io/packagist/v/contributte/nextras-orm-events.svg?style=flat-square)](https://packagist.org/packages/contributte/nextras-orm-events)
 
## Discussion / Help
 
[![Join the chat](https://img.shields.io/gitter/room/contributte/contributte.svg?style=flat-square)](http://bit.ly/ctteg)

## Install

```sh
composer require contributte/nextras-orm-events
```

## Versions

| State       | Version | Branch   | PHP      | |
|-------------|---------|----------|----------|-|
| development | `^0.4`  | `master` | `>= 5.6` ||
| stable      | `^0.3`  | `master` | `>= 5.6` ||
| stable      | `^0.2`  | `master` | `>= 5.6` |(old namespace)|

## Usage

### Config

```yaml
extensions:
    orm.events: Contributte\Nextras\Orm\Events\DI\NextrasOrmEventsExtension
```

```yaml
services:
    - App\Model\BeforePersistListener
```

### Entity

Just add annotation `@<Before/Update>` to your entity.

```php
/**
 * @BeforeInsert(App\Model\BeforeInsertListener)
 * @BeforePersist(App\Model\BeforePersistListener)
 * @BeforeRemove(App\Model\BeforeRemoveListener)
 * @BeforeUpdate(App\Model\BeforeUpdateListener)
 * @AfterInsert(App\Model\AfterInsertListener)
 * @AfterPersist(App\Model\AfterPersistListener)
 * @AfterRemove(App\Model\AfterRemoveListener)
 * @AfterUpdate(App\Model\AfterUpdateListener)
 
 * @Lifecycle(App\Model\LifecycleListener)
 */
class Foo extends Entity
{
}

```

### Service

```php

namespace App\Model;

use Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener;
use Nextras\Orm\Entity\IEntity;

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

### Real example

```yaml
service:
    - App\Model\FooBeforeInsertListener
```

```php
/**
 * @BeforeInsert(App\Model\FooBeforeInsertListener)
 */
class Foo extends Entity
{
}
```

```php
// Generated container.. 

/**
 * @return FooRepository
 */
public function createServiceOrm__repositories__foo()
{
    $service = new FooRepository(
        $this->getService('orm.mappers.foo'),
        $this->getService('orm.dependencyProvider')
    );
    $service->setModel($this->getService('orm.model'));

    // ===== attaching events (provided by extension =====
        
    $service->onBeforeInsert[] = [
        $this->getService('App\Model\FooBeforeInsertListener'),
        'onBeforeInsert',
    ];
    
    // ===== attaching events ============================
    
    return $service;
}
```

That's all. Super ultra simple.

## Maintainers

<table>
  <tbody>
    <tr>
      <td align="center">
        <a href="https://github.com/f3l1x">
            <img width="150" height="150" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=150">
        </a>
        </br>
        <a href="https://github.com/f3l1x">Milan Felix Šulc</a>
      </td>
    </tr>
  </tbody>
</table>

-------

Thank you for testing, reporting and contributing.
