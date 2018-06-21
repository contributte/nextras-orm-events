# Nextras ORM Events

## Content

- [Config](#config)
- [Entity](#entity)
- [Service](#service)
- [Real example](#real-example)

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

    public function onBeforePersist(IEntity $entity): void
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
