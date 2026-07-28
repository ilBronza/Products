<?php

namespace IlBronza\Products\Http\Controllers\Sellable;

use IlBronza\Buttons\Button;
use IlBronza\Category\Models\Category;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Sellable\SellableCRUD;

use function __;

class SellableIndexController extends SellableCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('products.models.sellable.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.sellable.fieldsGroupsFiles.related')::getTracedFieldsGroup();
    }

    public function getIndexElements()
    {
        $result = $this->getModelClass()::with(
            'target',
            'category',
        )->withCount('quotations')
	        ->withCount('orders')
	        ->withCount('suppliers');

        if($type = request()->type)
        {
            $result->byType($type);

            $translatedType = trans("products::types.{$type}");

            $this->setPageTitle(
                trans('products::routes.ibProductssellables.byType', [
                    'type' => $translatedType
                ])
            );
        }

        $placeholder = $this->getModelClass()::make();

        if(method_exists($placeholder, 'scopeNotArchived'))
            $result->notArchived();

        return $result->get();
    }

}
