<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder;

use IlBronza\Products\Models\Interfaces\CustomRowInterface;
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
	public CustomRowInterface $relatedCustomRowType;

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

	public function getRowcontainerModel() : ProductPackageBaseRowcontainerModel
	{
		return $this->rowContainer;
	}

	public function getRelationName() : string
	{
		return $this->relationName;
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

	public function getRelatedRowPlaceholder() : CustomRowInterface
	{
		return $this->getRowcontainerModel()->{$this->getRelationName()}()->make();
	}

	public function getRelationPackagePrefix() : ? string
	{
		return $this->getRelatedRowPlaceholder()->getDesignedTargetConfigPackagePrefix();
	}

	public function getButtonsMethods() : array
	{
		return array_keys(
			array_filter(
				config("{$this->getRelationPackagePrefix()}.models.orderrow.relatedButtonsMethods")
			)
		);
	}

	public function _getStandardRowrelationParameters()
	{
		$result = [
			'controller' => config("products.models.{$this->relatedBaseRowType}.controllers.index"),
			'selectRowCheckboxes' => true,
			'onlyButtonsDom' => true,
			'footerFilters' => false,
			'elementGetterMethod' => $this->getElementsGetterMethod(),
			'fieldsGroupsParametersFile' => $this->getFieldsgroupParametersFile(),
			'translatedTitle' => trans('products::rows.' . $this->relationName),
			'buttonsMethods' => $this->getButtonsMethods()
		];

		return $result;
	}

	static function getStandardRowrelationParameters(ProductPackageBaseRowcontainerModel $rowcontainer, string $relationName)
	{
		$helper = new static($rowcontainer, $relationName);

		return $helper->_getStandardRowrelationParameters();
	}
}