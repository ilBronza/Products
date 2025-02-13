<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Quotation\QuotationCRUD;

use function config;
use function ini_set;

class QuotationIndexController extends QuotationCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('products.models.quotation.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
		//QuotationRelatedFieldsGroupParametersFile
        return config('products.models.quotation.fieldsGroupsFiles.related')::getFieldsGroup();
    }

    public function getIndexElements()
    {
		ini_set('max_execution_time', 300);
		ini_set('memory_limit', - 1);

		return $this->getModelClass()::with(
			'extraFields',
            'project',
            'destination',
            'parent',
            'client',
			'order',
        )->get();
    }

}
