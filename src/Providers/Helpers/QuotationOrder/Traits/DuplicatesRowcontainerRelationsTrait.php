<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder\Traits;

use IlBronza\Products\Providers\Helpers\QuotationOrder\DuplicateRelationConfigHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

use function array_key_exists;
use function class_basename;
use function config;
use function method_exists;

trait DuplicatesRowcontainerRelationsTrait
{
	public function getDuplicateRelationsSelection() : array
	{
		return $this->formParameters['relations'] ?? [];
	}

	public function isRelationEnabled(string $relationKey) : bool
	{
		if (! array_key_exists($relationKey, $this->getDuplicateRelationsConfig()))
			return false;

		$selection = $this->getDuplicateRelationsSelection();

		if (! array_key_exists($relationKey, $selection))
			return $this->getDuplicateRelationConfig($relationKey)['default'] ?? true;

		$data = $selection[$relationKey];

		$enabledFlag = filter_var($data['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);

		if (! $this->isRelationMultipleForDuplicate($relationKey))
			return $enabledFlag;

		$instances = $data['instances'] ?? [];
		$hasInstances = is_array($instances) && array_filter($instances) !== [];

		return $enabledFlag || $hasInstances;
	}

	public function getSelectedRelationInstanceIds(string $relationKey) : array
	{
		if (! $this->isRelationEnabled($relationKey))
			return [];

		$instances = $this->getDuplicateRelationsSelection()[$relationKey]['instances'] ?? [];

		if (! is_array($instances))
			return [];

		return array_values(array_filter($instances));
	}

	protected function getRowcontainerModelConfigPrefix() : string
	{
		return $this->originalRowContainer::$modelConfigPrefix;
	}

	protected function isRelationMultipleForDuplicate(string $relationKey) : bool
	{
		$config = $this->getDuplicateRelationConfig($relationKey);
		$relationName = $config['relation'] ?? $relationKey;

		if (! method_exists($this->originalRowContainer, $relationName))
			return true;

		$relation = $this->originalRowContainer->$relationName();

		if (! $relation instanceof Relation)
			return true;

		$explicit = array_key_exists('multiple', $config) ? (bool) $config['multiple'] : null;

		return DuplicateRelationConfigHelper::resolveMultiple($relation, $explicit);
	}

	public function getDuplicateRelationConfig(string $relationKey) : array
	{
		return $this->getDuplicateRelationsConfig()[$relationKey] ?? [];
	}

	public function getDuplicateRelationsConfig() : array
	{
		$fromConfig = config('products.models.' . $this->getRowcontainerModelConfigPrefix() . '.duplicateRelations');

		return DuplicateRelationConfigHelper::normalizedDuplicateRelationsMap(
			is_array($fromConfig) ? $fromConfig : []
		);
	}

	public function duplicateConfiguredRelations() : void
	{
		foreach ($this->getDuplicateRelationsConfig() as $relationKey => $relationConfig)
		{
			if (($relationConfig['handler'] ?? 'default') === 'rows')
				continue;

			if (! $this->isRelationEnabled($relationKey))
				continue;

			$this->duplicateRelationByConfig($relationKey, $relationConfig);
		}
	}

	protected function duplicateSelectedRows() : void
	{
		foreach ($this->getRowsToDuplicate() as $rowType => $rows)
			foreach($rows as $row)
				$this->duplicateRow($rowType, $row);
	}

	public function getRowsToDuplicate() : array
	{
		$rows = [];

		foreach ($this->getDuplicateRelationsConfig() as $relationKey => $parameters)
		{
			if (($parameters['handler'] ?? null) !== 'rows')
				continue;

			$selectedIds = $this->getSelectedRelationInstanceIds($relationKey);

			$rows[$relationKey] = $this->originalRowContainer->$relationKey()->with('extraFields')->whereIn('id', $selectedIds)->get();
		}

		return $rows;
	}

	/**
	 * @param  array<string, mixed>  $relationConfig
	 */
	protected function loadDuplicateRowModelsFromRowBucket(string $relationKey, array $relationConfig, array $selectedIds) : Collection
	{
		$container = $this->originalRowContainer;
		$relationName = $relationConfig['relation'] ?? $relationKey;

		if (! method_exists($container, $relationName))
			return collect();

		$relation = $container->$relationName();

		if (! $relation instanceof Relation)
			return collect();

		$query = $relation->getQuery();

		if ($eagerLoad = $relationConfig['eagerLoad'] ?? null)
		{
			if (is_string($eagerLoad))
				$query->with([$eagerLoad]);
			else
				$query->with($eagerLoad);
		}

		$keyName = $query->getModel()->getKeyName();

		$query->whereIn($keyName, $selectedIds);

		return $query->get();
	}

	protected function duplicateRelationByConfig(string $relationKey, array $relationConfig) : void
	{
		$relationName = $relationConfig['relation'] ?? $relationKey;

		if (! method_exists($this->originalRowContainer, $relationName))
			return;

		$items = $this->loadRelationItemsForDuplication($relationName, $relationConfig, $relationKey);

		$handler = $relationConfig['handler'] ?? 'default';

		match ($handler)
		{
			'notes' => $this->duplicateNotesRelation($items),
			'dossiers' => $this->duplicateDossiersRelation($items),
			default => $this->duplicateDefaultRelation($relationName, $items, $relationConfig),
		};
	}

	protected function loadRelationItemsForDuplication(string $relationName, array $relationConfig, string $relationKey) : Collection
	{
		$relation = $this->originalRowContainer->$relationName();

		if (! $relation instanceof Relation)
			return collect();

		$query = $relation->getQuery();

		if ($eagerLoad = $relationConfig['eagerLoad'] ?? null)
			$query->with($eagerLoad);

		$explicit = array_key_exists('multiple', $relationConfig)
			? (bool) $relationConfig['multiple']
			: null;

		$multiple = DuplicateRelationConfigHelper::resolveMultiple($relation, $explicit);

		$selectedIds = $this->getSelectedRelationInstanceIds($relationKey);

		if ($selectedIds === null)
			return collect();

		if ($multiple)
		{
			if ($selectedIds === [])
				return collect();

			$keyName = $query->getModel()->getKeyName();

			$query->whereIn($keyName, $selectedIds);
		}

		return $query->get();
	}

	protected function duplicateDefaultRelation(string $relationName, Collection $items, array $relationConfig) : void
	{
		$relation = $this->originalRowContainer->$relationName();

		foreach ($items as $item)
		{
			$newItem = $item->replicate();

			$this->attachReplicatedRelationItem($relation, $newItem, $item, $relationConfig);
		}
	}

	protected function attachReplicatedRelationItem($relation, Model $newItem, Model $originalItem, array $relationConfig) : void
	{
		$relation->save($newItem);

		foreach ($relationConfig['nestedRelations'] ?? [] as $nestedRelationName)
		{
			if (! method_exists($originalItem, $nestedRelationName))
				continue;

			foreach ($originalItem->$nestedRelationName as $nestedItem)
			{
				$newNestedItem = $nestedItem->replicate();
				$newNestedItem->{$nestedItem->getForeignKey()} = $newItem->getKey();
				$newNestedItem->save();
			}
		}
	}

	protected function duplicateNotesRelation(Collection $notes) : void
	{
		foreach ($notes as $note)
		{
			$newNote = $note->replicate();

			$this->newRowContainer->notes()->save($newNote);

			if (! method_exists($note, 'media'))
				continue;

			foreach ($note->media as $media)
			{
				$media->copy(
					$newNote,
					$media->collection_name,
					$media->disk
				);
			}
		}
	}

	protected function duplicateDossiersRelation(Collection $dossiers) : void
	{
		$morphType = method_exists($this->newRowContainer, 'getMorphClass')
			? $this->newRowContainer->getMorphClass()
			: class_basename($this->newRowContainer);

		foreach ($dossiers as $dossier)
		{
			$newDossier = $dossier->replicate();
			$newDossier->dossierable_type = $morphType;
			$newDossier->dossierable_id = $this->newRowContainer->getKey();
			$newDossier->save();

			if (! method_exists($dossier, 'dossierrows'))
				continue;

			foreach ($dossier->dossierrows as $dossierrow)
			{
				$newDossierRow = $dossierrow->replicate();
				$newDossierRow->dossier_id = $newDossier->getKey();
				$newDossierRow->save();
			}
		}
	}
}
