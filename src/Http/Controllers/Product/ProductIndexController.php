<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Category\Traits\CRUDIndexHasCategoriesTrait;
use Illuminate\Support\Str;

class ProductIndexController extends ProductCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;
    use CRUDIndexHasCategoriesTrait;

    public $allowedMethods = ['index'];

    public function addIndexButtons()
    {
        $this->getTable()->addButton(
            $this->getInteractsWithCategoryButton()
        );

        $this->getTable()->setRowSelectCheckboxes(true);
    }

    public function getIndexFieldsArray()
    {
        return config('products.models.product.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.product.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getClientRelatedFieldsArray()
    {
        return config('products.models.product.fieldsGroupsFiles.byClientIndex')::getTracedFieldsGroup();
    }

    public function _getIndexElementsByScope(string $scope = null)
    {
        $query = $this->getModelClass()::withCount(['media', 'prices', 'categories', 'orders', 'activeOrders', 'productRelations']);

        if($scope)
            $query->$scope();

        return $query->get();
    }

    public function getIndexElements()
    {
        return $this->_getIndexElementsByScope();
    }
}
