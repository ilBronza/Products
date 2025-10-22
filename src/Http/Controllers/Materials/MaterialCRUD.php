<?php

namespace IlBronza\Products\Http\Controllers\Materials;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

use IlBronza\Products\Http\Controllers\CRUDProductPackageController;
use Illuminate\Support\Str;

class MaterialCRUD extends CRUDProductPackageController
{
	public $configModelClassName = 'material';
}