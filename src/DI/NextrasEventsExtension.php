<?php

namespace Minetro\Nextras\Events\DI;

use Minetro\Nextras\Events\Listeners\AfterInsertListener;
use Minetro\Nextras\Events\Listeners\AfterPersistListener;
use Minetro\Nextras\Events\Listeners\AfterRemoveListener;
use Minetro\Nextras\Events\Listeners\AfterUpdateListener;
use Minetro\Nextras\Events\Listeners\BeforeInsertListener;
use Minetro\Nextras\Events\Listeners\BeforePersistListener;
use Minetro\Nextras\Events\Listeners\BeforeRemoveListener;
use Minetro\Nextras\Events\Listeners\BeforeUpdateListener;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceCreationException;
use Nette\Reflection\ClassType;
use Nextras\Orm\Repository\IRepository;

final class NextrasEventsExtension extends CompilerExtension
{

    /** @var array */
    private static $annotations = [
        'Lifecycle' => [
            'onBeforeInsert' => BeforeInsertListener::class,
            'onBeforePersist' => BeforePersistListener::class,
            'onBeforeRemove' => BeforeRemoveListener::class,
            'onBeforeUpdate' => BeforeUpdateListener::class,
            'onAfterInsert' => AfterInsertListener::class,
            'onAfterPersist' => AfterPersistListener::class,
            'onAfterRemove' => AfterRemoveListener::class,
            'onAfterUpdate' => AfterUpdateListener::class,
        ],
        'BeforeInsert' => [
            'onBeforeInsert' => BeforeInsertListener::class,
        ],
        'BeforePersist' => [
            'onBeforePersist' => BeforePersistListener::class,
        ],
        'BeforeRemove' => [
            'onBeforeRemove' => BeforeRemoveListener::class,
        ],
        'BeforeUpdate' => [
            'onBeforeUpdate' => BeforeUpdateListener::class,
        ],
        'AfterInsert' => [
            'onAfterInsert' => AfterInsertListener::class,
        ],
        'AfterPersist' => [
            'onAfterPersist' => AfterPersistListener::class,
        ],
        'AfterRemove' => [
            'onAfterRemove' => AfterRemoveListener::class,
        ],
        'AfterUpdate' => [
            'onAfterUpdate' => AfterUpdateListener::class,
        ],
    ];

    public function beforeCompile()
    {
        // Find registered IRepositories and parse their entities
        $mapping = $this->loadEntityMapping();

        // Attach listeners
        $this->loadListeners($mapping);
    }

    /**
     * @return array
     */
    private function loadEntityMapping()
    {
        $mapping = [];

        $builder = $this->getContainerBuilder();
        $repositories = $builder->findByType(IRepository::class);

        foreach ($repositories as $repository) {
            $repositoryClass = $repository->getEntity();

            // Skip invalid repositoryClass name
            if (!class_exists($repositoryClass)) {
                throw new ServiceCreationException(sprintf("Repository class '%s' not found", $repositoryClass));
            }

            // Skip invalid subtype ob IRepository
            if (!method_exists($repositoryClass, 'getEntityClassNames')) continue;

            // Append mapping [repository => [entity1, entity2, entityN]
            foreach ($repositoryClass::getEntityClassNames() as $entity) {
                $mapping[$entity] = $repositoryClass;
            }
        }

        return $mapping;
    }

    /**
     * @param array $mapping
     * @return void
     */
    private function loadListeners($mapping)
    {
        foreach ($mapping as $entity => $repository) {
            // Test invalid class name
            if (!class_exists($entity)) {
                throw new ServiceCreationException(sprintf("Entity class '%s' not found", $entity));
            }

            // Parse annotations from phpDoc
            $rf = new ClassType($entity);

            // Add entity as dependency
            $this->getContainerBuilder()->addDependency($rf->getFileName());

            // Try all annotations
            foreach (self::$annotations as $annotation => $events) {
                if (($listener = $rf->getAnnotation($annotation))) {
                    $this->loadListenerByAnnotation($events, $repository, $listener);
                }
            }
        }
    }

    /**
     * @param string $pair
     * @param string $repository
     * @param string $listener
     */
    private function loadListenerByAnnotation($events, $repository, $listener)
    {
        $builder = $this->getContainerBuilder();

        // Skip if repository is not registered in DIC
        if (($rsn = $builder->getByType($repository)) === NULL) {
            throw new ServiceCreationException(sprintf("Repository service '%s' not found", $repository));
        }

        // Skip if listener is not registered in DIC
        if (($lsn = $builder->getByType($listener)) === NULL) {
            throw new ServiceCreationException(sprintf("Listener service '%s' not found", $listener));
        }

        // Get definitions
        $repositoryDef = $builder->getDefinition($rsn);
        $listenerDef = $builder->getDefinition($lsn);

        foreach ($events as $event => $interface) {
            // Check implementation
            $rf = new ClassType($listener);
            if ($rf->implementsInterface($interface) === FALSE) {
                throw new ServiceCreationException(sprintf("Object '%s' should implement '%s'", $listener, $interface));
            }

            $repositoryDef->addSetup('$service->?[] = ?', [
                $event,
                [$listenerDef, $event],
            ]);
        }
    }

}
