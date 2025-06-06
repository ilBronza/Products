<?php

namespace IlBronza\Products\Models\Traits\Product;

use Carbon\Carbon;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\OrderProduct;

trait ProductScopesTrait
{
	public function scopeWithLastOrderId($query)
	{
        $query->addSelect([
            'live_last_order_id' => OrderProduct::getProjectClassName()::select('order_id')
                    ->whereColumn('products__order_products.product_id', $this->getTable() . '.id')
                    ->orderBy('completed_at', 'DESC')
                    ->take(1)
        ]);
	}

	public function scopeWithLastOrderProductId($query)
	{
        $query->addSelect([
            'live_last_order_product_id' => OrderProduct::getProjectClassName()::select('id')
                    ->whereColumn('products__order_products.product_id', $this->getTable() . '.id')
                    ->orderBy('completed_at', 'DESC')
                    ->take(1)
        ]);
	}

	public function scopeByPallettype($query, string $pallettypeId)
	{
		$query->whereHas('packing', function($_query) use($pallettypeId)
			{
				$_query->where('pallettype_id', $pallettypeId);
			})
			->orWhere(function($_query) use($pallettypeId)
			{
				$_query->whereHas('packing', function($__query)
				{
					$__query->whereNull('pallettype_id');
				})
				->whereHas('client', function($__query) use($pallettypeId)
				{
					$__query->whereHas('extraFields', function($___query) use($pallettypeId)
					{
						$___query->where('pallettype_id', $pallettypeId);
					});
				})
				;
			})
			;
	}

	public function scopeByClient($query, mixed $client)
	{
		if($client instanceof Client)
			$client = $client->getKey();

		$query->where('client_id', $client);
	}

	public function scopeHavingActiveOrderProducts($query)
	{
		$query->whereHas(
			'orderProducts',
			function($_query)
			{
				$_query->whereNull('completed_at');
			}
		);
	}

    public function scopeByName($query, string $name)
    {
        $query->where('name', $name);
    }

	public function scopeWithLastOrderProduct($query)
	{
		$query->withLastOrderProductId()->with('lastOrderProduct');
	}

	public function scopeCurrent($query)
	{
		$query->whereHas(
			'orderProducts',
			function($_query)
			{
				$_query->where('completed_at', '>' , Carbon::now()->subMonths(6));
			}
		);
	}

}