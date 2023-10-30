<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;

class ProductEditUpdateController extends ProductCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.product.parametersFiles.edit');
    }

    public function addEditExtraViews()
    {
        $this->addFormExtraView('outherTop', 'preventivatore.quotations.sizesScripts', [
            'product' => $this->getModel()
        ]);
    }

    public function edit($product)
    {
        $product = $this->findModel($product, ['size', 'extraFields']);

        return $this->_edit($product);
    }

    public function update(Request $request, $product)
    {
        $product = $this->findModel($product);

        foreach([
            'long_side_places',
            'short_side_places',
            'options_glued',
            'options_glueing',
            'options_double_manual_glueing',
            'options_stretched',
            'options_flat_cord',
            'options_mounted',
            'options_die_cut'
        ] as $field)
        {
            if(in_array($field, array_keys($request->all())))
                $product->getSize()->$field = $request->input($field);
        }

        $product->getSize()->save();

        return $this->_update($request, $product);
    }
}
