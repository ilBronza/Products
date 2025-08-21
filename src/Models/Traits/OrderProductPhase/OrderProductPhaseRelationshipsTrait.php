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
use Illuminate\Support\Facades\Log;

trait OrderProductPhaseRelationshipsTrait
{
	public function client()
	{
		return $this->belongsTo(Client::getProjectClassName(), 'live_client_id', 'id');
	}

	public function getClient() : ? Client
	{
		if(in_array('live_client_id', $this->getAttributes()))
			return $this->client;

		return $this->getOrder()?->getClient();
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
		return $this->product;
	}

	public function orderProductPhases()
	{
		return $this->hasMany(static::getProjectClassName(), 'order_product_id', 'order_product_id');
	}

	public function getOrderProductPhases() : Collection
	{
		return $this->orderProductPhases;
		// return $this->getOrFindCachedRelation('orderProductPhases');
	}

	public function previous()
	{
		return $this->belongsTo(static::class, 'live_previous_id', 'id');
	}

	public function next()
	{
		return $this->belongsTo(static::class, 'live_next_id', 'id');
	}

	public function first()
	{
		return $this->belongsTo(static::class, 'live_first_order_product_phase_id', 'id');
	}

	public function last()
	{
		return $this->belongsTo(static::class, 'live_last_order_product_phase_id', 'id');
	}

	public function getNextOrderProductPhase() : ? static
	{
		// if($this->relationLoaded('next'))
		// 	return $this->next;

		// Log::critical('Check here, use withNext() scope');

		return $this->getOrderProductPhases()->firstWhere('sorting_index', $this->getSequence() + 1);
	}

	public function getFirstOrderProductPhase() : ? static
	{
		if($this->relationLoaded('first'))
			return $this->first;

		return $this->getOrderProductPhases()->sortBy('sorting_index')->first();
	}

	public function getLastOrderProductPhase() : ? static
	{
		if($this->relationLoaded('last'))
			return $this->last;

		return $this->getOrderProductPhases()->sortByDesc('sorting_index')->first();
	}

	public function getPreviousOrderProductPhase() : ? static
	{
		// if($this->relationLoaded('previous'))
		// 	return $this->previous;

		// Log::critical('Check here, use withPrevious() scope');

		return $this->getOrderProductPhases()->sortByDesc('sorting_index')->firstWhere('sorting_index', '<', $this->getSequence());
	}

}