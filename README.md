![](https://heatbadger.now.sh/github/readme/contributte/nextras-orm-events/)

<p align=center>
    <a href="https://github.com/contributte/nextras-orm-events/actions"><img src="https://badgen.net/github/checks/contributte/nextras-orm-events"></a>
    <a href="https://codecov.io/gh/contributte/nextras-orm-events"><img src="https://badgen.net/codecov/c/github/contributte/nextras-orm-events"></a>
    <a href="https://packagist.org/packages/contributte/nextras-orm-events"><img src="https://badgen.net/packagist/dm/contributte/nextras-orm-events"></a>
    <a href="https://packagist.org/packages/contributte/nextras-orm-events"><img src="https://badgen.net/packagist/v/contributte/nextras-orm-events"></a>
</p>
<p align=center>
    <a href="https://packagist.org/packages/contributte/nextras-orm-events"><img src="https://badgen.net/packagist/php/contributte/nextras-orm-events"></a>
    <a href="https://github.com/contributte/nextras-orm-events"><img src="https://badgen.net/github/license/contributte/nextras-orm-events"></a>
    <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
    <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
    <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
    Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Doctrine-like events for Nextras ORM entity lifecycle hooks.

## Versions

|  State   | Version |  Branch      | Nette  | PHP     |
|----------|---------|--------------|--------|---------|
|  dev     | `^0.10` |  `master`    | `3.4+` | `>=8.1` |
|  stable  | `^0.9`  |  `master`    | `3.1+` | `>=8.1` |

## Installation

To install latest version of `contributte/nextras-orm-events` use [Composer](https://getcomposer.org).

```bash
composer require contributte/nextras-orm-events
```

Register extension in your configuration.

```neon
extensions:
    orm.events: Contributte\Nextras\Orm\Events\DI\NextrasOrmEventsExtension
```

## Usage

### Listener

Create listener.

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

Register it.

```neon
services:
    - App\Model\ExampleBeforePersistListener
```

### Entity

Add lifecycle annotations to your entity or to a trait used by your entity.

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
 * @Flush(App\Model\FlushListener)
 *
 * @Lifecycle(App\Model\LifecycleListener)
 */
class Foo extends Entity
{
}
```

## Real Example

```neon
services:
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

    // ===== attaching events (provided by extension) =====

    $service->onBeforeInsert[] = [
        $this->getService('App\Model\FooBeforeInsertListener'),
        'onBeforeInsert',
    ];

    // ===== attaching events =============================

    return $service;
}
```

## Development

See [how to contribute](https://contributte.org) to this package. This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars.githubusercontent.com/f3l1x">
</a>

-----

Consider to [support](https://contributte.org/partners) **contributte** development team.
Also thank you for using this package.
