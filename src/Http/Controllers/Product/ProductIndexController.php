<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

use Illuminate\Support\Str;

class ProductIndexController extends ProductCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

//	public $avoidCreateButton = true;

    public function __construct()
    {
        parent::__construct();

        ini_set('max_execution_time', "120");
        ini_set('memory_limit', "-1");
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

    public $allowedMethods = ['index'];

    public function _getIndexElementsByScope(string $scope = null)
    {
//        return cache()->remember(
//
//            Str::slug(get_class($this) . __METHOD__),
//            3600 * 24,
//
//            function() use($scope)
//            {
                $query = $this->getModelClass()::withCount(['media', 'prices', 'categories', 'orders', 'activeOrders', 'productRelations']);

                if($scope)
                    $query->$scope();

                return $query->get();
//            }
//        );
    }

    public function getIndexElements()
    {
        return $this->_getIndexElementsByScope();
    }

}
