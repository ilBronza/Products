<?php

namespace IlBronza\Products\Http\Controllers\Workstations;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

use IlBronza\Products\Http\Controllers\CRUDProductPackageController;
use Illuminate\Support\Str;

class WorkstationCRUD extends CRUDProductPackageController
{
	public $configModelClassName = 'workstation';
}