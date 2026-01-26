<?php

namespace IlBronza\Products\Models;

use Auth;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\Products\Models\Traits\Assignee\ProductAssignmentTrait;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductCalculatedTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductCheckerTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductGetterSetterTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductScopesTrait;
use IlBronza\Products\Providers\Helpers\OrderProducts\OrderProductCompletionHelper;
use IlBronza\Timings\Interfaces\HasTimingInterface;
use IlBronza\Timings\Traits\InteractsWithTimingTrait;
use IlBronza\Warehouse\Helpers\Unitloads\UnitloadCreatorHelper;
use IlBronza\Warehouse\Models\Interfaces\DeliverableInterface;
use IlBronza\Warehouse\Models\Traits\InteractsWithDeliveryTrait;
use IlBronza\Warehouse\Models\Unitload\Unitload;
use Illuminate\Support\Collection;

class OrderProduct extends ProductPackageBaseModel implements HasTimingInterface, DeliverableInterface
{
	use InteractsWithTimingTrait;
	use OrderProductRelationshipsTrait;
	use OrderProductScopesTrait;
	use OrderProductGetterSetterTrait;
	use OrderProductCalculatedTrait;
	use OrderProductCheckerTrait;

	use ProductAssignmentTrait;
	use CompletionScopesTrait;

	use InteractsWithDeliveryTrait;

	protected $touches = ['order'];

	static $deletingRelationships = ['orderProductPhases'];
	static $restoringRelationships = ['orderProductPhases'];
	static $modelConfigPrefix = 'orderProduct';
	public $classnameAbbreviation = 'op';

	public function getTimingChildren() : Collection
	{
		return $this->getOrderProductPhases();
	}

	public function getTimingFather() : ?HasTimingInterface
	{
		return $this->getOrder();
	}

	public function getIndexUrl(array $data = [])
	{
		return null;
	}

	public function getDestination()
	{
		if ($this->destination)
			return $this->destination;

		return $this->getOrder()->getDestination();
	}

	public function getCalculatedDestinationIdAttribute() : string
	{
		if ($this->destination_id)
			return $this->destination_id;

		if ($destinationId = $this->getOrder()?->getDestination()?->getKey())
			return $destinationId;

		return $this->getClient()->getDefaultDestination()->getKey();
	}

	public function getDestinationId() : ?string
	{
		return $this->getDestination()?->getKey();
	}

	public function getProductUrl()
	{
		return IbRouter::route(app('products'), 'products.show', ['product' => $this->getProductId()]);
	}

	public function hasAllOrderProductPhasesCompleted() : bool
	{
		foreach ($this->getOrderProductPhases() as $orderProductPhase)
			if (! $orderProductPhase->isCompleted())
				return false;

		return true;
	}

	public function unitloads()
	{
		return $this->hasMany(
			Unitload::gpc(),
		);
	}

	public function scopeWithUnitloadsCounts($query)
	{
		$query->withCount([
			'unitloads',
			'unitloads as unitloads_with_delivery_count' => function ($_query)
				{
					$_query->delivering();
				},
			'unitloads as unitloads_without_delivery_count' => function ($_query)
				{
					$_query->notDelivering();
				},
		]);
	}

	public function getUnitloads() : Collection
	{
		return $this->unitloads()->get();
	}

	public function getPallettypeId()
	{
		return $this->getPallettypeItem()?->getKey();
	}

	public function getUnitloadsByArrivedCarboardsQuantity()
	{
		if(! $arrived = $this->getOrderedCardboardArrived())
			return ;

		if(! $coefficientOutput = $this->getCoefficientOutput())
			return ;

		return UnitloadCreatorHelper::provideByModelsQuantity(
			$this->getProduct(),
			$this->getLastOrderProductPhase(),
			$coefficientOutput * $arrived,
			[
				'destination_id' => $this->getDestinationId(),
				'pallettype_id' => $this->getPallettypeId()
			]
		);		
	}

	public function getUnitloadsByClientQuantity()
	{
		return UnitloadCreatorHelper::provideByModelsQuantity(
			$this->getProduct(),
			$this->getLastOrderProductPhase(),
			$this->getClientQuantity(),
			[
				'destination_id' => $this->getDestinationId(),
				'pallettype_id' => $this->getPallettypeId()
			]
		);
	}

	public function getQuantityRequired() : ?float
	{
		return $this->quantity_required;
	}

	// private function bindDataFromLastOrderProductPhase()
	// {
	// 	if (! $lastOrderProductPhase = $this->getLastOrderProductPhase())
	// 		throw new Exception('Ultima fase non trovata per componente ' . $this->getName() . ' <a href="' . $this->getEditUrl() . '">Controlla qui</a>');

	// 	$this->setCompletedAt(
	// 		$lastOrderProductPhase->getCompletedAt()
	// 	);

	// 	$this->setQuantityDone(
	// 		$lastOrderProductPhase->getQuantityDone()
	// 	);
	// }

	public function reset()
	{
		OrderProductCompletionHelper::gpc()::reset($this);

		// foreach($this->getOrderProductPhases() as $orderProductPhase)
		// 	$orderProductPhase->reset();

		// $this->setStateId(null);
		// $this->setQuantityDone(null);
		// $this->setManualPiecesDone(null);
		// $this->setManualPiecesDoneUpdatedBy(null);
		// $this->setManualCardboardsToUnload(null);
		// // $this->setCardboardsCountDifference(null);
		// $this->setBrokenCount(null);
		// $this->setPartiallyCompletedAt(null);
		// $this->setStartedAt(null);
		// $this->setCompletedAt(null);

		// $this->save();

		// $this->getOrder()->uncomplete();

		// return $this;
	}

//	private function uncomplete()
//	{
//		OrderProductCompletionHelper::gpc()::uncomplete($this);
//
//		// $this->bindDataFromLastOrderProductPhase();
//
//		// $this->setStateId(null);
//		// $this->save();
//
//		// TimingRemoverHelper::remove($this);
//	}

//	private function complete()
//	{
//		OrderProductCompletionHelper::gpc()::complete($this);
//
//		// $this->bindDataFromLastOrderProductPhase();
//
//		// $this->setStateId(
//		// 	State::getTerminatedState()->id
//		// );
//
//		// $this->save();
//	}

	public function getProductionUnitloads() : Collection
	{
		return $this->getUnitloadsByClientQuantity();
	}

	public function getProductionStatusList() : ? string
	{
		return $this->getOrderProductPhasesProductionList();
	}

	public function getDeliveringChildren() : Collection
	{
		return collect();
	}

	public function getClientKey() : ? string
	{
		return $this->getOrder()->getClientKey();
	}
}