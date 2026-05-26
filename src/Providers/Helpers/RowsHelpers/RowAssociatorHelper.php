<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use IlBronza\Ukn\Ukn;
use Illuminate\Support\Collection;

class RowAssociatorHelper
{
	public ProductPackageBaseRowcontainerModel $containerModel;
	public SellableSupplier $sellableSupplier;
	public Sellable $sellable;
	public Supplier $supplier;
	public string $type;

	public ProductPackageBaseRowModel $row;

	public array $addedSellableSuppliers = [];

	public function __construct(ProductPackageBaseRowcontainerModel $containerModel, SellableSupplier|string $sellableSupplier = null)
	{
		$this->containerModel = $containerModel;

		if(! $sellableSupplier)
			return ;

		if(is_string($sellableSupplier))
			$sellableSupplier = SellableSupplier::gpc()::with('sellable', 'supplier')->find($sellableSupplier);

		$this->sellableSupplier = $sellableSupplier;

		if(! $sellableSupplier->getSellable())
			throw new \Exception('controllare "' . $sellableSupplier->getSupplier()->getTarget()->getName() . '" manca la relazione con il bene in vendita');

		$this->sellable = $sellableSupplier->getSellable();
	}

	static function createBySellableSupplier(ProductPackageBaseRowcontainerModel $containerModel, SellableSupplier|string $sellableSupplier)
	{
		return new static($containerModel, $sellableSupplier);
	}

	static function createBySupplier(ProductPackageBaseRowcontainerModel $containerModel, Supplier|string $supplier) : static
	{
		$helper = new static($containerModel);

		$helper->setSupplier(
			$supplier
		);

		return $helper;
	}

	static function createBySellable(ProductPackageBaseRowcontainerModel $containerModel, Sellable|string $sellable) : static
	{
		$helper = new static($containerModel);

		$helper->setSellable(
			$sellable
		);

		return $helper;
	}

	public function setSellable(Sellable $sellable)
	{
		$this->sellable = $sellable;
	}

	public function setSupplier(Supplier $supplier)
	{
		$this->supplier = $supplier;
	}

	public function getSupplier() : ? Supplier
	{
		return $this->supplier;
	}

	static function associateRowBySellable(ProductPackageBaseRowcontainerModel $containerModel, Sellable|string $sellable)
	{
		$helper = static::createBySellable($containerModel, $sellable);

		$helper->makeRow();
		$helper->associateSellableToRow();

		$helper->row->save();
	}

	static function associateRowBySupplier(ProductPackageBaseRowcontainerModel $containerModel, Supplier|string $supplier, string $type)
	{
		$sellable = Sellable::provideGenericByType($type);

		if(is_string($supplier))
			$supplier = Supplier::gpc()::find($supplier);

		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);

		return static::associateRowBySellableSupplier($containerModel, $sellableSupplier);
	}

	static function associateRowBySellableSupplier(ProductPackageBaseRowcontainerModel $containerModel, SellableSupplier|string $sellableSupplier)
	{
		$helper = static::createBySellableSupplier($containerModel, $sellableSupplier);

		return $helper->_associateRowBySellableSupplier();
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function hasSellable() : bool
	{
		return isset($this->sellable);
	}

	public function getSellableTarget() : ? SellableItemInterface
	{
		return $this->getSellable()?->getTarget();
	}

	public function getSellableSupplier() : SellableSupplier
	{
		return $this->sellableSupplier;
	}

	public function setType(string $type)
	{
		$this->type = $type;
	}

	public function getType() : string
	{
		if(isset($this->type))
			return $this->type;

		if($this->hasSellable())
			return $this->getSellable()->getType();

		throw new \Exception('non deve succedere');
	}

	public function addAddedSellableSupplier(SellableSupplier $sellableSupplier)
	{
		$this->addedSellableSuppliers[] = $sellableSupplier->getKey();
	}

	public function setDependentSellableSuppliers()
	{
		$this->sellableSuppliersToInsert = collect();
		$this->sellablesToInsert = collect();

		if(! $target = $this->getSellableTarget())
			return collect();

		foreach($target->getDependentSellables() as $relation)
		{
			foreach($target->$relation as $item)
			{
				if($item instanceof SellableItemInterface)
				{
					if(! $sellable = $item->getSellable())
					{
						$this->sellablesToInsert->push(
							SellableCreatorHelper::getOrcreateSellableByTarget($item)
						);

						continue;
					}

					$sellableSuppliers = $sellable->getSellableSuppliers();

					if(count($sellableSuppliers) == 1)
					{
						Ukn::w('C\'era un solo elemento del tipo ' . $item->getName() . ' ho già aggiunto il bene preciso invece di quello generico');

						$sellableSuppliers = $sellableSuppliers->first();

						$this->sellableSuppliersToInsert->push($sellableSuppliers);
					}

					else
						$this->sellablesToInsert->push(
							$sellable
						);
				}

				elseif($item instanceof SupplierInterface)
				{
					if(! $supplier = $item->getSupplier())
						$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($item);

					$sellableSuppliers = $supplier->getValidSellableSuppliers();

					if(count($sellableSuppliers) == 1)
						$this->sellableSuppliersToInsert->push(
							$sellableSuppliers->first()
						);

					elseif(count($sellableSuppliers) == 0)
					{
						// dd($supplier, $supplier->getTarget(), $supplier->getTarget()->getName());
					}

					else
					{
						Ukn::e('Aggiunti ' . count($sellableSuppliers) . ' tipi di correlato invece che 1 da ' . $supplier->getTarget()?->getName());

						foreach($sellableSuppliers as $sellableSupplier)
							$this->sellableSuppliersToInsert->push(
								$sellableSupplier
							);
					}
				}
				else
				{
					dd('non appartiene alla classe corretta maybe?');
				}
			}
		}
	}

	public function makeRow() : static
	{
        $classMethod = "rowRelationBy{$this->getType()}";

        $this->row = $this->containerModel->{$classMethod}()->make();
		// $this->row = $this->containerModel->rows()->make();

		$this->row->container()->associate(
			$this->containerModel
		);

		return $this;
	}

	public function getDependentSellables() : Collection
	{
		if(! $target = $this->getSellableTarget())
			return collect();

		$items = collect();

		//foreach($target->getDependentSellables())

		//dd('qua gestiamo tutto assieme, vediamo se è sellable o sellableSupplier e andiamo via');

		dd($target->getDependentSellables());
	}

	public function setParentRow(ProductPackageBaseRowModel $row) : static
	{
		$this->row->associateParent($row);

		return $this;
	}

	public function associateSellableToRow()
	{
		$this->row->sellable()->associate(
			$this->getSellable()
		);

		$this->row->type = $this->getType();
		$this->row->sorting_index = RowsFinderHelper::getSortingIndexByType(
			$this->containerModel,
			$this->getType()
		);
	}

	public function associateContainerToRow()
	{
		$this->row->sellable()->associate(
			$this->getSellable()
		);
	}

	public function _associateRowBySellableSupplier() : static
	{
		$this->makeRow();
		$this->associateSellableToRow();

		$this->row->save();

		RowsSellableSupplierAssociatorHelper::associateSellableSupplierToRow(
			$this->row,
			$this->getSellableSupplier()
		);

		$this->addAddedSellableSupplier(
			$this->getSellableSupplier()
		);

		$this->setDependentSellableSuppliers();

		foreach($this->sellableSuppliersToInsert as $sellableSupplier)
			static::createBySellableSupplier(
						$this->containerModel,
						$sellableSupplier
					)
					->_associateRowBySellableSupplier()
					->setParentRow(
						$this->row
					);

		foreach($this->sellablesToInsert as $sellable)
		{
			static::associateRowBySellable($this->containerModel, $sellable);
		}

		return $this;
	}
}