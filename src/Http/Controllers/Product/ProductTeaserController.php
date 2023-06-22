<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDTeaserTrait;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;
use IlBronza\Products\Models\Product\Product;

class ProductTeaserController extends ProductCRUD
{
    use CRUDRelationshipTrait;
    use CRUDTeaserTrait;

    public $allowedMethods = ['teaser'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.product.parametersFiles.teaser');
    }

    public function teaser(Product $product)
    {
        return $this->_teaser($product);
    }
}
