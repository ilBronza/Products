<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use IlBronza\Ukn\Ukn;
use Illuminate\Support\Collection;

class RowAssociatorHelper
{
	public ProductPackageBaseRowcontainerModel $containerModel;
	public SellableSupplier $sellableSupplier;
	public Sellable $sellable;

	public ProductPackageBaseRowModel $row;

	public array $addedSellableSuppliers = [];

	public function __construct(ProductPackageBaseRowcontainerModel $containerModel, SellableSupplier|string $sellableSupplier)
	{
		$this->containerModel = $containerModel;

		if(is_string($sellableSupplier))
			$sellableSupplier = SellableSupplier::gpc()::with('sellable', 'supplier')->find($sellableSupplier);

		$this->sellableSupplier = $sellableSupplier;

		if(! $sellableSupplier->getSellable())
			throw new \Exception('controllare "' . $sellableSupplier->getSupplier()->getTarget()->getName() . '" manca la relazione con il bene in vendita');

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

	public function getSellableTarget() : SellableItemInterface
	{
		return $this->getSellable()->getTarget();
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

	public function setDependentSellableSuppliers()
	{
		if(! $target = $this->getSellableTarget())
			return collect();

		$this->sellableSuppliersToInsert = collect();
		$this->sellablesToInsert = collect();

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
						$this->sellablesToInsert->push($item);
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
						dd($supplier->getTarget()->getName());

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

	public function _associateRowBySellableSupplier() : static
	{
		$this->row = $this->containerModel->rows()->make();

		$this->row->sellable()->associate(
			$this->getSellable()
		);

		$this->row->container()->associate(
			$this->containerModel
		);

		$this->row->type = $this->getType();
		$this->row->sorting_index = RowsFinderHelper::getSortingIndexByType(
			$this->containerModel,
			$this->getType()
		);

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
			static::create(
						$this->containerModel,
						$sellableSupplier
					)
					->_associateRowBySellableSupplier()
					->setParentRow(
						$this->row
					);

		foreach($this->sellablesToInsert as $sellable)
		{
			dd($sellable);
			$helper = static::create($this->containerModel, $sellableSupplier);

			$helper->_associateRowBySellableSupplier();

			dd($helper->setParentRow($this->row));

			dd($sellableSuppliersArray = $helper->getAddedSellableSuppliers());
		}

		return $this;
	}
}