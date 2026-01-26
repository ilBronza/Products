<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierCRUD;

use function config;

class SellableSupplierIndexController extends SellableSupplierCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

	public function getIndexFieldsArray()
	{
		//SellableSupplierFieldsGroupParametersFile
		return config('products.models.sellableSupplier.fieldsGroupsFiles.index')::getTracedFieldsGroup();
	}

	public function getRelatedFieldsArray()
	{
		//SellableSupplierFieldsGroupParametersFile
		return config('products.models.sellableSupplier.fieldsGroupsFiles.index')::getTracedFieldsGroup();
	}

	public function getIndexElements()
    {
        return $this->getModelClass()::with(
			'supplier.target',
			'sellable.target',
			'prices'
		)
	        ->withCount('quotationrows')
	        ->withCount('orderrows')
			->get();
    }

}
