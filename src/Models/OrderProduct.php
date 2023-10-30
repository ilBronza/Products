<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductGetterSetterTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductScopesTrait;

class OrderProduct extends ProductPackageBaseModel
{
	use OrderProductRelationshipsTrait;
	use OrderProductScopesTrait;
	use OrderProductGetterSetterTrait;

	use CompletionScopesTrait;

	static $modelConfigPrefix = 'orderProduct';

	public function getIndexUrl(array $data = [])
	{
		return null;
	}

	public function getDestination()
	{
		if($this->destination)
			return $this->destination;

		return $this->getOrder()->getDestination();
	}

	public function getCalculatedDestinationIdAttribute() : string
	{
		if($this->destination_id)
			return $this->destination_id;

		if($destinationId = $this->getOrder()?->getDestination()?->getKey())
			return $destinationId;

		return $this->getClient()->getDefaultDestination()->getKey();
	}







	public function getProductUrl()
	{
		return IbRouter::route(app('products'), 'products.show', ['product' => $this->getProductId()]);
	}

	public function hasAllOrderProductPhasesCompleted() : bool
	{
		foreach($this->getOrderProductPhases() as $orderProductPhase)
			if(! $orderProductPhase->isCompleted())
				return false;

		return true;
	}
}