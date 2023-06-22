<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductScopesTrait;

class OrderProduct extends ProductPackageBaseModel
{
	use OrderProductRelationshipsTrait;
	use OrderProductScopesTrait;

	use CompletionScopesTrait;

	static $modelConfigPrefix = 'orderProduct';

	public function getIndexUrl(array $data = [])
	{
		return null;
	}

	//TODO trasformarlo in relazione per quando serve come da Laracasts eloquent mastering - ultimo accesso last login video
	public function getLastOrderProductPhase() : OrderProductPhase
	{
		if(! $this->relationLoaded('orderProductPhases'))
			return $this->orderProductPhases()->orderByDesc('sequence')->first();

		return $this->orderProductPhases->sortByDesc('sequence')->first();
	}

	public function getQuantityRequired() : int
	{
		return $this->quantity_required;
	}

	public function getCalculatedDestinationIdAttribute() : string
	{
		if($this->destination_id)
			return $this->destination_id;

		if($destinationId = $this->getOrder()?->getDestination()?->getKey())
			return $destinationId;

		return $this->getClient()->getDefaultDestination()->getKey();
	}
}