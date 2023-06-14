<?php

namespace IlBronza\Products\Http\Controllers\Accessory;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use Illuminate\Http\Request;

class AccessoryCrudController extends AccessoryCRUD
{
	use CRUDPlainIndexTrait;
	use CRUDIndexTrait;
	use CRUDCreateStoreTrait;
    use CRUDEditUpdateTrait;

	public $allowedMethods = [
		'index',
		'create',
		'store',
        'edit',
        'update'
	];

    public function getCreateParametersFile() : ? string
    {
        return config('products.models.accessory.parametersFiles.create');
    }

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.accessory.parametersFiles.crud');
    }

	public function getIndexFieldsArray()
	{
		return config('products.models.accessory.fieldsGroupsFiles.index')::getFieldsGroup();
	}
	
	public function getIndexElements()
	{
		return $this->getModelClass()::all();
	}

    public function edit($accessory)
    {
        $accessory = $this->findModel($accessory);

        return $this->_edit($accessory);
    }

    public function update(Request $request, $accessory)
    {
        $accessory = $this->findModel($accessory);

        return $this->_update($request, $accessory);
    }
}
