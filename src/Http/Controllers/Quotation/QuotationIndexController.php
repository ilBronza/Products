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
        return config('products.models.quotation.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
		//QuotationRelatedFieldsGroupParametersFile
        return config('products.models.quotation.fieldsGroupsFiles.related')::getTracedFieldsGroup();
    }

    public function getIndexElements()
    {
        $query = $this->getModelClass()::with(
            'project',
            'destination',
            'parent',
            'client',
            'order',
        );

        if(method_exists($this->getModelClass(), 'extraFields'))
            $query->with('extraFields');


		return $query->get();
    }

}
