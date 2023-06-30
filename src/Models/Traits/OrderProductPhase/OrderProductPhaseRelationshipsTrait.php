<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use App\Workstation;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Product\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait OrderProductPhaseRelationshipsTrait
{
	public function client()
	{
		return $this->belongsTo(Client::getProjectClassName(), 'live_client_id', 'id');
	}

    public function workstation()
    {
        return $this->belongsTo(
        	Workstation::class,
        	'workstation_overridden_id'
        );
    }

	public function getWorkstation()
	{
		if($this->relationLoaded('workstation'))
			if($this->workstation)
				return $this->workstation;

		if($workstation = Workstation::findCached(
					$this->getWorkstationId()
				))
			return $workstation;

		dd($this);
	}

	public function orderProduct()
	{
		return $this->belongsTo(OrderProduct::getProjectClassName());
	}

	public function getOrderProduct() : ? OrderProduct
	{
		return $this->getOrFindCachedRelation('orderProduct');
	}

	public function phase()
	{
		return $this->belongsTo(Phase::getProjectClassName());
	}

	public function getPhase() : ? Phase
	{
		return $this->getOrFindCachedRelation('phase');
	}

	public function order()
	{
		return $this->hasOneThrough(
            Order::getProjectClassName(),
            OrderProduct::getProjectClassName(),
            'id', // refers to id column on order_products table
            'id', // refers to id column on orders table
            'order_product_id',
            'order_id' // refers to order_id column on order_products table
        );
	}

	public function getOrder() : ? Order
	{
		return $this->getOrFindCachedRelation('order');
	}

	public function product()
	{
		return $this->hasOneThrough(
            Product::getProjectClassName(),
            OrderProduct::getProjectClassName(),
            'id', // refers to id column on order_products table
            'id', // refers to id column on orders table
            'order_product_id',
            'product_id' // refers to order_id column on order_products table
        );
	}

	public function getProduct() : ? Product
	{
		return $this->getOrFindCachedRelation('product');
	}

	public function orderProductPhases()
	{
		return $this->hasMany(static::getProjectClassName(), 'order_product_id', 'order_product_id');
	}

	public function getOrderProductPhases(array $relations = null) : Collection
	{
		return $this->getOrFindCachedRelation('orderProductPhases', $relations);
	}

	public function getPreviousOrderProductPhase() : ? static
	{
		return $this->orderProductPhases()->get()->firstWhere('sequence', $this->getSequence() - 1);

		// if($this->getKey() == 'e21f37a9-7785-4508-8656-13863e584830')
		// {
		// 	dd($this);
		// 	DB::enableQueryLog();

		// 	$prev = $this->orderProductPhases()->where([
		// 		'sequence' => function($query)
		// 		{
		// 			$query->select(DB::raw('sequence'))
		// 				->from('products__order_product_phases as opp')
		// 				->whereRaw('opp.id = products__order_product_phases.id');
		// 		}
		// 	])->first();

		// 	// dd($prev);

		// 	dd(DB::getQueryLog());

		// 	dd($prev);

		// }

		// return $this->previous;
	}

	public function next()
	{
		return $this->getOrderProductPhases()->where('sequence', $this->getSequence() + 1);
	}

	public function getNextOrderProductPhase()
	{
		return $this->next;
	}

}