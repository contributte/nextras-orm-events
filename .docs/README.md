# Nextras ORM Events

Doctrine-like events for Nextras ORM entity lifecycle.

## Content

- [Setup](#setup)
- [Usage](#usage)
  - [Listener](#listener)
  - [Entity](#entity)
- [Real example](#real-example)

## Setup

Install package

```bash
composer require contributte/nextras-orm-events
```

RegisterExtension

```yaml
extensions:
    orm.events: Contributte\Nextras\Orm\Events\DI\NextrasOrmEventsExtension
```

## Usage

### Listener

Create listener

```php
namespace App\Model;

use Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener as BaseBeforePersistListener;
use Nextras\Orm\Entity\IEntity;

final class BeforePersistListener implements BaseBeforePersistListener
{

    public function onBeforePersist(IEntity $entity): void
    {
        // ...
    }

}
```

Register it

```yaml
services:
    - App\Model\ExampleBeforePersistListener
```

### Entity

Just add annotation `@<Before/Update>` to your entity or to trait which entity uses.

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

## Real example

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
