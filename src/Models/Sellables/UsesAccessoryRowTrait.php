<?php

namespace IlBronza\Products\Models\Sellables;

use Illuminate\Support\Collection;

trait UsesAccessoryRowTrait
{
	public function initializeUsesAccessoryRowTrait()
	{
		$this->addFieldsToUpdateByRowTypes('accessoryRows');

		$this->addSummaryFieldsCastsByRowTypes('accessoryRows');
	}

	// AccessoryOrderrow or AccessoryQuotationrow
	abstract public function getAccessoryRowRelatedModel() : string;

	public function rowRelationByAccessoryType()
	{
		return $this->accessoryRows();
	}

	public function accessoryRows()
	{
		return $this->hasMany(
			$this->getAccessoryRowRelatedModel()
		);
	}

	public function getAccessoryRows()
	{
		return $this->accessoryRows;
	}

	public function getAddAccessoryTypeUrl() : string
	{
		return $this->getAddRowByTypeUrl('AccessoryType');
	}

	public function getAccessoryRowsForRelationshipManager() : Collection
	{
		$modelString = strtolower(class_basename($this->getModel()));

		if(method_exists($this->getModel(), 'getExtraFieldsClass'))
			if($this->getModel()->getExtraFieldsClass())
				$modelString .= '.extraFields';

		$relations = [
			"{$modelString}",
			'prices',
			'sellable',
			'sellable.target',
			'sellable.prices',
			'sellableSupplier.prices',
			'sellableSupplier.supplier.target',
			'sellableSupplier.sellable.target',
		];

		$accessoryRowPlaceholder = $this->accessoryRows()->make();

		if(method_exists($accessoryRowPlaceholder, 'getExtraFieldsClass'))
			if($accessoryRowPlaceholder->getExtraFieldsClass())
				$relations[] = 'extraFields';

		return $this->accessoryRows()->with($relations)->get();
	}
}