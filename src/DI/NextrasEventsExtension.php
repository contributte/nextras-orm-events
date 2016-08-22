<?php

namespace Minetro\Nextras\Events\DI;

use Nette\DI\CompilerExtension;
use Nette\Reflection\ClassType;
use Nextras\Orm\Repository\IRepository;

final class NextrasEventsExtension extends CompilerExtension
{

    /** @var array */
    private static $annotations = [
        'Lifecycle' => [
            'onBeforeInsert',
            'onBeforePersist',
            'onBeforeRemove',
            'onBeforeUpdate',
            'onAfterInsert',
            'onAfterPersist',
            'onAfterRemove',
            'onAfterUpdate',
        ],
        'BeforeInsert' => ['onBeforeInsert'],
        'BeforePersist' => ['onBeforePersist'],
        'BeforeRemove' => ['onBeforeRemove'],
        'BeforeUpdate' => ['onBeforeUpdate'],
        'AfterInsert' => ['onAfterInsert'],
        'AfterPersist' => ['onAfterPersist'],
        'AfterRemove' => ['onAfterRemove'],
        'AfterUpdate' => ['onAfterUpdate'],
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
            $class = $repository->getEntity();

            // Skip invalid class name
            if (!class_exists($class)) continue;

            // Skip invalid subtype ob IRepository
            if (!method_exists($class, 'getEntityClassNames')) continue;

            // Append mapping [repository => [entity1, entity2, entityN]
            foreach ($class::getEntityClassNames() as $entity) {
                $mapping[$entity] = $class;
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
            // Skip invalid class name
            if (!class_exists($entity)) continue;

            // Parse annotations from phpDoc
            $rf = new ClassType($entity);

            // Add entity as dependency
            $this->getContainerBuilder()->addDependency($rf->getFileName());

            // Try all annotations
            foreach (self::$annotations as $annotation => $events) {
                if (($listener = $rf->getAnnotation($annotation))) {
                    $this->loadListenerByAnnotation([$annotation, $events], $repository, $listener);
                }
            }
        }
    }

    /**
     * @param string $pair
     * @param string $repository
     * @param string $listener
     */
    private function loadListenerByAnnotation($pair, $repository, $listener)
    {
        $builder = $this->getContainerBuilder();

        // Parse annotation and it's method
        list ($annotation, $events) = $pair;

        // Skip if repository is not registered in DIC
        if (($rsn = $builder->getByType($repository)) === NULL) return;

        // Skip if listener is not registered in DIC
        if (($lsn = $builder->getByType($listener)) === NULL) return;

        // Get definitions
        $repositoryDef = $builder->getDefinition($rsn);
        $listenerDef = $builder->getDefinition($lsn);

        foreach ($events as $event) {
            $repositoryDef->addSetup('$service->?[] = ?', [
                $event,
                [$listenerDef, $event],
            ]);
        }
    }

}
