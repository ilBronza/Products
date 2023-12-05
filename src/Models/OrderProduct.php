<?php

namespace IlBronza\Products\Models;

use App\State;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductCalculatedTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductCheckerTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductGetterSetterTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductScopesTrait;

class OrderProduct extends ProductPackageBaseModel
{
	use OrderProductRelationshipsTrait;
	use OrderProductScopesTrait;
	use OrderProductGetterSetterTrait;
	use OrderProductCalculatedTrait;
	use OrderProductCheckerTrait;

	use CompletionScopesTrait;

	static $deletingRelationships = ['orderProductPhases'];
	static $restoringRelationships = ['orderProductPhases'];

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


	public function checkCompletion()
	{
		if($this->orderProductPhases()->notCompleted()->count() > 0)
			return $this->uncomplete();

		return $this->complete();
	}

	private function bindDataFromLastOrderProductPhase()
	{
		if(! $lastOrderProductPhase = $this->getLastOrderProductPhase())
			throw new \Exception('Ultima fase non trovata per componente ' . $this->getName() . ' <a href="' . $this->getEditUrl() . '">Controlla qui</a>');

		$this->setCompletedAt(
			$lastOrderProductPhase->getCompletedAt()
		);

		$this->setQuantityDone(
			$lastOrderProductPhase->getQuantityDone()
		);
	}

	private function uncomplete()
	{
		$this->bindDataFromLastOrderProductPhase();

		$this->setStateId(null);
		$this->save();

		$timing = $this->timing()->get();

		foreach($timing as $_timing)
			$_timing->deleterForceDelete();
	}

	private function complete()
	{
		$this->bindDataFromLastOrderProductPhase();

		$this->setStateId(
			State::getTerminatedState()->id
		);

		$this->save();
	}

}