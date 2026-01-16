<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Providers\Helpers\PriceCreatorHelpers\SellablePricesCreatorHelper;

use function cache;

class ProductCalculatePricesController extends ProductCRUD
{
    public $allowedMethods = ['calculatePrices'];

    public function calculatePrices()
    {
	    $products = $this->getModelClass()::all();

		foreach($products as $product)
			cache()->remember(
				$product->cacheKey('calculatePricesByTarget'),
				3600,
				function() use ($product)
				{
					return SellablePricesCreatorHelper::calculatePricesByTarget($product);
				}
			);

		return response()->json([
			'status' => 'ok'
		]);
    }
}
