<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Support\Collection;

class RowAssociatorHelper
{
	public ProductPackageBaseRowcontainerModel $containerModel;
	public SellableSupplier $sellableSupplier;
	public Sellable $sellable;

	public array $addedSellableSuppliers = [];

	public function __construct(ProductPackageBaseRowcontainerModel $containerModel, SellableSupplier|string $sellableSupplier)
	{
		$this->containerModel = $containerModel;

		if(is_string($sellableSupplier))
			$sellableSupplier = SellableSupplier::gpc()::with('sellable', 'supplier')->find($sellableSupplier);

		$this->sellableSupplier = $sellableSupplier;
		$this->sellable = $sellableSupplier->getSellable();
	}

	static function create(ProductPackageBaseRowcontainerModel $containerModel, SellableSupplier|string $sellableSupplier)
	{
		return new static($containerModel, $sellableSupplier);
	}

	static function associateRowBySellableSupplier(ProductPackageBaseRowcontainerModel $containerModel, SellableSupplier|string $sellableSupplier)
	{
		$helper = static::create($containerModel, $sellableSupplier);

		return $helper->_associateRowBySellableSupplier();
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function getSellableSupplier() : SellableSupplier
	{
		return $this->sellableSupplier;
	}

	public function getType() : string
	{
		return $this->getSellable()->getType();
	}

	public function addAddedSellableSupplier(SellableSupplier $sellableSupplier)
	{
		$this->addedSellableSuppliers[] = $sellableSupplier->getKey();
	}

	public function getDependentSellables() : Collection
	{
		if(! $target = $this->getSellable()->getTarget())
			return collect();

		$items = collect();

		//foreach($target->getDependentSellables())

		dd('qua gestiamo tutto assieme, vediamo se è sellable o sellableSupplier e andiamo via');

		dd($target->getDependentSellables());
	}

	public function _associateRowBySellableSupplier()
	{
		$row = $this->containerModel->rows()->make();

		$row->sellable()->associate(
			$this->getSellable()
		);

		$row->container()->associate(
			$this->containerModel
		);

		$row->type = $this->getType();
		$row->sorting_index = RowsFinderHelper::getSortingIndexByType(
			$this->containerModel,
			$this->getType()
		);

		$row->save();

		RowsSellableSupplierAssociatorHelper::associateSellableSupplierToRow(
			$row,
			$this->getSellableSupplier()
		);

		$this->addAddedSellableSupplier(
			$this->getSellableSupplier()
		);

		$sellableSuppliers = $this->getDependentSellables();

		dd($sellableSuppliers);

		foreach($sellableSuppliers as $sellableSupplier)
		{
			$helper = static::create($this->containerModel, $sellableSupplier);

			$helper->_associateRowBySellableSupplier();

			dd($sellableSuppliersArray = $helper->getAddedSellableSuppliers());
		}

		dd($sellableSuppliers);

		Ukn::e('rifare tutto con la stessa logica per i sellables generici');
		$sellables = $this->getDependentSellables();

		return $this;
	}
}