<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use Illuminate\Support\Collection;

use function json_encode;

abstract class QuotationDuplicatorHelper
{
	public array $formParameters;
	public ProductPackageBaseRowcontainerModel $originalRowContainer;
	public ProductPackageBaseRowcontainerModel $newRowContainer;
	public Collection $rows;

	public function __construct(ProductPackageBaseRowcontainerModel $originalRowContainer, array $formParameters)
	{
		$this->originalRowContainer = $originalRowContainer;
		$this->formParameters = $formParameters;

		$this->loadRows();
	}

	public function getOriginalRowContainer() : ProductPackageBaseRowcontainerModel
	{
		return $this->originalRowContainer;
	}

	public function loadRows()
	{
		$this->rows = $this->getOriginalRowContainer()->rows()->with('extraFields')->get();
	}

	public function duplicateRowContainer()
	{
		$container = $this->getOriginalRowContainer();

		$this->newRowContainer = $container->replicate();

		$this->newRowContainer->name = $this->newRowContainer->calculateNewName();
		$this->newRowContainer->frozen = false;

		$this->newRowContainer->save();

		$this->newRowContainerExtraFields = $container->extraFields->replicate();
		$this->newRowContainer->extraFields()->save($this->newRowContainerExtraFields);

		$this->newRowContainer->frozen_parameters = null;
		$this->newRowContainer->save();
	}

	public function duplicateRows()
	{
		foreach($this->getRows() as $row)
			$this->duplicateRow($row);
	}

	public function getRows() : Collection
	{
		return $this->rows;
	}

	public function getFormParameters() : array
	{
		return $this->formParameters;
	}

	public function duplicateRow(ProductPackageBaseRowModel $row)
	{
		$newRow = $row->replicate();
		$newRow->frozen = false;

		$this->newRowContainer->rows()->save($newRow);

		if($row->extraFields)
		{
			$newRowExtraFields = $row->extraFields->replicate();
			$newRow->extraFields()->save($newRowExtraFields);
		}

		$newRow->frozen_parameters = null;
		$newRow->save();
	}

	abstract public function recalculateParametersByForm();

	public function duplicate()
	{
		$this->duplicateRowContainer();

		$this->duplicateRows();

		$this->recalculateParametersByForm();

		$this->newRowContainer->load('rows.extraFields');

		return $this->newRowContainer;
	}


}