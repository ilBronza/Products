<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use Illuminate\Support\Collection;

use function dd;
use function json_encode;

abstract class FreezerHelper
{
	public ProductPackageBaseRowcontainerModel $rowContainer;
	public Collection $rows;
	public function __construct(ProductPackageBaseRowcontainerModel $rowContainer)
	{
		$this->rowContainer = $rowContainer;

		$this->loadRows();
		$this->setRowsFrozenValues();
		$this->freezeRowContainer();
	}

	public function getRowContainer() : ProductPackageBaseRowcontainerModel
	{
		return $this->rowContainer;
	}

	public function loadRows()
	{
		$this->rows = $this->getRowContainer()->rows()->with('extraFields', 'sellableSupplier.supplier.target')->get();
	}

	public function getRows() : Collection
	{
		return $this->rows;
	}

	public function setRowsFrozenValues()
	{
		foreach($this->getRows() as $row)
		{
			$pieces = [];

			foreach($row->getFieldsToFreeze() as $field)
				$row->$field = $row->$field;

			foreach($row->getFieldsToStoreAsJson() as $field)
				$pieces[$field] = $row->$field;

			$row->frozen_parameters = json_encode($pieces);
			$row->frozen = true;

			$row->save();
		}
	}

	public function freezeRowContainer()
	{
		$container = $this->getRowContainer();

		foreach($container->getFieldsToStoreAsJson() as $field)
			$pieces[$field] = $container->$field;

		$container->frozen_parameters = json_encode($pieces);
		$container->frozen = true;

		$container->save();

	}
}