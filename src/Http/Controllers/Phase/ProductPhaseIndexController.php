<?php

namespace IlBronza\Products\Http\Controllers\Phase;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Phase\PhaseCRUD;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\RelatedPhaseFieldsGroupParametersFile;

class ProductPhaseIndexController extends PhaseCRUD
{
    public $debug = true;

    public $allowedMethods = ['index'];

    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getRelatedFieldsArray()
    {
        return RelatedPhaseFieldsGroupParametersFile::getFieldsGroup();
    }
}
