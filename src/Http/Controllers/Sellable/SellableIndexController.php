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
        return config('products.models.sellable.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.sellable.fieldsGroupsFiles.related')::getFieldsGroup();
    }

	public function addIndexButtons()
	{
		$this->table->addButton(
			Button::create([
				'translatedText' => __('products::sellables.generateAllMissing'),
				'icon' => 'gauge-high',
				'href' => app('products')->route('sellables.buildBulk')
			])
		);
	}

    public function getIndexElements()
    {
        return $this->getModelClass()::with(
            'target',
            'category',
        )->withCount('quotations')
	        ->withCount('orders')
	        ->withCount('suppliers')
            ->get();
    }

}
