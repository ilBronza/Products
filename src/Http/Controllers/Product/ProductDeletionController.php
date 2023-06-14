<?php

namespace IlBronza\Products\Http\Controllers\Product;

class ProductDeletionController extends ProductCRUD
{
    public $allowedMethods = ['destroy'];

    public function destroy($product)
    {
        $product = $this->findModel($product);

        dd("qua continuare con la cancellazione");

        return $this->_edit($product);
    }
}
