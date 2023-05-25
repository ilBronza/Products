<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\CRUD;

class ProductCRUD extends CRUD
{
    public function setModelClass()
    {
        dd(config('products.models.product.class'));
        $this->modelClass = config('products.models.product.class');
    }

}
