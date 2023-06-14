<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;

use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductRelationshipsTrait;

class OrderProduct extends ProductPackageBaseModel
{
	static $modelConfigPrefix = 'orderProduct';

	use OrderProductRelationshipsTrait;

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