<?php

namespace IlBronza\Products\Models\Sellables;

use Illuminate\Support\Collection;

trait UsesProductRowTrait
{
	public function initializeUsesProductRowTrait()
	{
		$this->setRowRelationsParameters('productRows');
	}

	// ProductOrderrow or ProductQuotationrow
	abstract public function getProductRowRelatedModel() : string;

	public function rowRelationByProduct()
	{
		return $this->productRows();
	}

	public function productRows()
	{
		return $this->hasMany(
			$this->getProductRowRelatedModel()
		);
	}

	public function getProductRows()
	{
		return $this->productRows;
	}

	public function getAddProductUrl() : string
	{
		return $this->getAddRowByTypeUrl('Product');
	}

	public function getProductRowsForRelationshipManager() : Collection
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

		$productRowPlaceholder = $this->productRows()->make();

		if(method_exists($productRowPlaceholder, 'getExtraFieldsClass'))
			if($productRowPlaceholder->getExtraFieldsClass())
				$relations[] = 'extraFields';

		$result = $this->productRows()->with($relations)
			->withCount('genericChildren')
			->get();

			// return $result;

		$result = $result->filter(function($item)
		{
			if($item->getSellable()?->getName() != 'Hotel')
				return true;

			if($item->calculated_row_total > 0)
				return true;

			if($item->generic_children_count == 0)
				return true;

			return false;
		});

		return ProductionOrderrow::addSplittedRowsToResult($result);
	}
}