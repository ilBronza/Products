<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;

use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;

use function config;
use function dd;
use function trans;

class RowsSellableSupplierAssociatorHelper
{
	public ProductPackageBaseRowModel $row;
	public Supplier $supplier;
	public SellableSupplier $sellableSupplier;

	static function provideHelper()
	{
		$class = config('products.models.sellableSupplier.helpers.rowSellableSupplierAssociatorHelper');

		return new $class();
	}

	public function setRow(ProductPackageBaseRowModel $row)
	{
		$this->row = $row;
	}

	public function getRow() : ProductPackageBaseRowModel
	{
		return $this->row;
	}

	public function getSupplier() : Supplier
	{
		return $this->supplier;
	}

	public function setSupplier(Supplier $supplier)
	{
		$this->supplier = $supplier;
	}

	public function getSellable() : Sellable
	{
		if(! isset($this->sellable))
			if(! $this->sellable = $this->row->getSellable())
				throw new \Exception(
					trans('products::errors.rowDoesntHaveSellable', [
					'row' => $this->row->getName(),
				]));

		return $this->sellable;
	}

	public function setSellableSupplier(string|SellableSupplier $sellableSupplier)
	{
		if(is_string($sellableSupplier))
			$sellableSupplier = SellableSupplier::gpc()::find($sellableSupplier);

		$this->sellableSupplier = $sellableSupplier;
	}

	public function provideSellableSupplier() : SellableSupplier
	{
		if(! isset($this->sellableSupplier))
			$this->sellableSupplier = SellableCreatorHelper::getOrCreateSellableSupplier(
				$this->getSupplier(),
				$this->getSellable()
			);

		return $this->sellableSupplier;
	}

	public function _associateSellableSupplierToRow()
	{
		$row = $this->getRow();

		$row->sellableSupplier()->associate(
			$this->provideSellableSupplier()
		);

		$row->save();
	}

	static function emptySellableSupplier(ProductPackageBaseRowModel $row)
	{
		$helper = static::provideHelper();

		$helper->setRow($row);

		return $helper->removeSupplierFromRow();
	}

	public function removeSupplierFromRow()
	{
		$row = $this->getRow();
		$row->sellable_supplier_id = null;
		$row->save();
	}

	static function associateSellableSupplierToRowBySupplier(ProductPackageBaseRowModel $row, Supplier $supplier)
	{
		$helper = static::provideHelper();

		$helper->setRow($row);
		$helper->setSupplier($supplier);

		return $helper->_associateSellableSupplierToRow();
	}

	static function associateSellableSupplierToRow(ProductPackageBaseRowModel $row, SellableSupplier|string $sellableSupplier)
	{
		$helper = static::provideHelper();

		$helper->setRow($row);
		$helper->setSellableSupplier($sellableSupplier);

		return $helper->_associateSellableSupplierToRow();

	}
}