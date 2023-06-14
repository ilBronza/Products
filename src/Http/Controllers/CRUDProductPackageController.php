<?php

namespace IlBronza\Products\Http\Controllers;

use IlBronza\CRUD\CRUD;

class CRUDProductPackageController extends CRUD
{
    public function getRouteBaseNamePrefix() : ? string
    {
        return config('products.routePrefix');
    }

    public function setModelClass()
    {
        $this->modelClass = config("products.models.{$this->configModelClassName}.class");
    }
}
