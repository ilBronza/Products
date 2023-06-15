<?php

namespace IlBronza\Products\Http\Controllers\Product;

class ProductCurrentController extends ProductIndexController
{
    public function getIndexElements()
    {
        return $this->_getIndexElementsByScope('current');
    }
}
