<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder;

use IlBronza\Products\Models\Orders\CustomOrderrow;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

class RowscontainerRelationsManagerParametersHelper
{
	/**
	 * Order or Quotation
	 **/
	public ProductPackageBaseRowcontainerModel $rowContainer;

	/**
	 * es. vehicleRows()
	 **/
	public string $relationName;

	/**
	 * es VehicleRow
	 **/
	public CustomOrderrow $relatedCustomRowType;

	/**
	 * orderrow or quotationrow
	 **/
	public string $relatedBaseRowType;


	public function __construct(ProductPackageBaseRowcontainerModel $rowcontainer, string $relationName)
	{
		$this->rowContainer = $rowcontainer;
		$this->relationName = $relationName;

		$this->relatedCustomRowType = $this->rowContainer->$relationName()->getRelated();

		//orderrow - quotationrow
		$this->relatedBaseRowType = $this->rowContainer->rows()->getRelated()->getModelConfigPrefix();

		$this->packagePrefix = $this->relatedCustomRowType::getDesignedTargetConfigPackagePrefix();
	}

	public function getFieldsgroupParametersFile() : string
	{
		return config("{$this->packagePrefix}.models.{$this->relatedBaseRowType}.fieldsGroupsFiles.index");
	}

	public function getElementsGetterMethod() : string
	{
		$ucFirstRelation = ucfirst($this->relationName);

		return "get{$ucFirstRelation}ForRelationshipManager";
	}

	static function getStandardRowrelationParameters(ProductPackageBaseRowcontainerModel $rowcontainer, string $relationName)
	{
		$helper = new static($rowcontainer, $relationName);

		$result = [
			'controller' => config("products.models.{$helper->relatedBaseRowType}.controllers.index"),
			'selectRowCheckboxes' => true,
			'onlyButtonsDom' => true,
			'footerFilters' => false,
			'elementGetterMethod' => $helper->getElementsGetterMethod(),
			'fieldsGroupsParametersFile' => $helper->getFieldsgroupParametersFile(),
			'translatedTitle' => trans('products::rows.' . $relationName),
			'buttonsMethods' => [
				'getAddSellableSupplierButton',
				'getAddRowButton',
				'getAddRowTableButton',
			]
		];

		return $result;
	}
}