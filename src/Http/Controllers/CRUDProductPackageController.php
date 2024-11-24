<?php

namespace IlBronza\Products\Http\Controllers;

use IlBronza\CRUD\Http\Controllers\BasePackageController;

class CRUDProductPackageController extends BasePackageController
{
	static $packageConfigPrefix = 'products';

//    public function getRouteBaseNamePrefix() : ? string
//    {
//        return config('products.routePrefix');
//    }

//    public function setModelClass()
//    {
//        $this->modelClass = config("products.models.{$this->configModelClassName}.class");
//    }
}
