<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

/**
 * Crea una riga col minimo indispensabile per essere una riga di quel tipo.
 * Nessun sellable: si assegna dopo editando la riga.
 **/
class EmptyRowCreatorHelper
{
	public ProductPackageBaseRowcontainerModel $containerModel;
	public string $type;

	public ProductPackageBaseRowModel $row;

	public function __construct(ProductPackageBaseRowcontainerModel $containerModel, string $type)
	{
		$this->containerModel = $containerModel;
		$this->type = $type;
	}

	static function createEmptyRowByType(ProductPackageBaseRowcontainerModel $containerModel, string $type) : static
	{
		$helper = new static($containerModel, $type);

		$helper->makeRow();
		$helper->setMinimumRowData();

		$helper->row->save();

		return $helper;
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function getRow() : ProductPackageBaseRowModel
	{
		return $this->row;
	}

	public function makeRow() : static
	{
		$classMethod = "rowRelationBy{$this->getType()}";

		$this->row = $this->containerModel->{$classMethod}()->make();

		$this->row->container()->associate(
			$this->containerModel
		);

		return $this;
	}

	public function setMinimumRowData() : static
	{
		$this->row->type = $this->getType();

		$this->row->sorting_index = RowsFinderHelper::getSortingIndexByRowType(
			$this->containerModel,
			$this->getType()
		);

		return $this;
	}
}
