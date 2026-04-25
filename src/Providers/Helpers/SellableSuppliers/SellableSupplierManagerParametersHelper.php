<?php

// namespace IlBronza\Products\Providers\Helpers\SellableSuppliers;

// use IlBronza\Products\Models\Interfaces\SupplierInterface;
// use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\Lang;

// class SellableSupplierManagerParametersHelper
// {
// 	protected SupplierInterface $supplier;
// 	protected string $relationName = 'sellableSuppliers';

// 	static function getStandardRelationParametersBySupplier(SupplierInterface $supplier)
// 	{
// 		$helper = new static();

// 		$helper->setSupplier($supplier);

// 		return $helper->returnStandardRelationParametersBySupplier();
// 	}

// 	public function setSupplier(SupplierInterface $supplier) : void
// 	{
// 		$this->supplier = $supplier;
// 	}

// 	public function getElementsGetterMethodBySupplier() : string
// 	{
// 		// Implementato nei modelli che usano InteractsWithSupplierTrait
// 		return 'getSellableSuppliersBySupplier';
// 	}

// 	public function getFieldsgroupParametersFile() : string
// 	{
// 		return config("products.models.sellableSupplier.fieldsGroupsFiles.bySupplier");
// 	}

// 	public function getButtonsMethods() : array
// 	{
// 		return [];
// 	}

// 	public function returnStandardRelationParametersBySupplier()
// 	{
// 		$result = [
// 			'controller' => Config::get("products.models.sellableSupplier.controllers.index"),
// 			'selectRowCheckboxes' => true,
// 			'onlyButtonsDom' => true,
// 			'footerFilters' => false,
// 			'elementGetterMethod' => $this->getElementsGetterMethodBySupplier(),
// 			'fieldsGroupsParametersFile' => $this->getFieldsgroupParametersFile(),
// 			'translatedTitle' => Lang::get('products::relations.' . $this->relationName),
// 			'buttonsMethods' => $this->getButtonsMethods()
// 		];

// 		return $result;
// 	}
// }