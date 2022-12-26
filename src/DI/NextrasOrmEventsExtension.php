<?php declare(strict_types = 1);

namespace Contributte\Nextras\Orm\Events\DI;

use Contributte\Nextras\Orm\Events\Listeners\AfterInsertListener;
use Contributte\Nextras\Orm\Events\Listeners\AfterPersistListener;
use Contributte\Nextras\Orm\Events\Listeners\AfterRemoveListener;
use Contributte\Nextras\Orm\Events\Listeners\AfterUpdateListener;
use Contributte\Nextras\Orm\Events\Listeners\BeforeInsertListener;
use Contributte\Nextras\Orm\Events\Listeners\BeforePersistListener;
use Contributte\Nextras\Orm\Events\Listeners\BeforeRemoveListener;
use Contributte\Nextras\Orm\Events\Listeners\BeforeUpdateListener;
use Contributte\Nextras\Orm\Events\Listeners\FlushListener;
use Contributte\Utils\Annotations;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\ServiceCreationException;
use Nextras\Orm\Repository\IRepository;
use ReflectionClass;

final class NextrasOrmEventsExtension extends CompilerExtension
{

	/** @var string[][] */
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
			'onFlush' => FlushListener::class,
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
		'Flush' => [
			'onFlush' => FlushListener::class,
		],
	];

	/**
	 * Decorate services
	 */
	public function beforeCompile(): void
	{
		// Find registered IRepositories and parse their entities
		$mapping = $this->loadEntityMapping();

		// Attach listeners
		$this->loadListeners($mapping);
	}

	/**
	 * Load entity mapping
	 *
	 * @return string[]
	 */
	private function loadEntityMapping(): array
	{
		$mapping = [];

		$builder = $this->getContainerBuilder();
		$repositories = $builder->findByType(IRepository::class);

		foreach ($repositories as $repository) {
			assert($repository instanceof ServiceDefinition);

			/** @var string $repositoryClass */
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
	 * @param string[] $mapping
	 */
	private function loadListeners(array $mapping): void
	{
		$builder = $this->getContainerBuilder();

		foreach ($mapping as $entity => $repository) {
			// Test invalid class name
			if (!class_exists($entity)) {
				throw new ServiceCreationException(sprintf("Entity class '%s' not found", $entity));
			}

			$types = [$entity];
			$uses = class_uses($entity);
			if ($uses !== false) {
				$types = $uses + [$entity];
			}

			/** @var class-string $type */
			foreach ($types as $type) {
				// Parse annotations from phpDoc
				$rf = new ReflectionClass($type);

				// Add entity/trait as dependency
				$builder->addDependency($rf);

				// Try all annotations
				foreach (self::$annotations as $annotation => $events) {
					/** @var class-string|null $listener */
					$listener = Annotations::getAnnotation($rf, $annotation);
					if ($listener !== null) {
						$this->loadListenerByAnnotation($events, $repository, $listener);
					}
				}
			}
		}
	}

	/**
	 * @param string[] $events
	 * @param class-string $listener
	 */
	private function loadListenerByAnnotation(array $events, string $repository, string $listener): void
	{
		$builder = $this->getContainerBuilder();

		// Skip if repository is not registered in DIC
		if (($rsn = $builder->getByType($repository)) === null) {
			throw new ServiceCreationException(sprintf("Repository service '%s' not found", $repository));
		}

		// Skip if listener is not registered in DIC
		if (($lsn = $builder->getByType($listener)) === null) {
			throw new ServiceCreationException(sprintf("Listener service '%s' not found", $listener));
		}

		// Get definitions
		$repositoryDef = $builder->getDefinition($rsn);
		assert($repositoryDef instanceof ServiceDefinition);
		$listenerDef = $builder->getDefinition($lsn);

		// Check implementation
		$rf = new ReflectionClass($listener);

		foreach ($events as $event => $interface) {
			if ($rf->implementsInterface($interface) === false) {
				throw new ServiceCreationException(sprintf("Object '%s' should implement '%s'", $listener, $interface));
			}

			$repositoryDef->addSetup('$service->?[] = function() {call_user_func_array([?, ?], func_get_args());}', [
				$event,
				$listenerDef,
				$event,
			]);
		}
	}

}
