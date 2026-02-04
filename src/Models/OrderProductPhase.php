<?php

namespace IlBronza\Products\Models;

use App\Providers\Helpers\Processings\ProcessingCreatorHelper;
use Auth;
use Carbon\Carbon;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\Products\Models\Traits\Assignee\ProductAssignmentTrait;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseButtonsAndRouting;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseCheckerTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseGetterTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseProcessingsTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseScopesTrait;
use IlBronza\Products\Providers\Helpers\OrderProductPhases\OrderProductPhaseCompletionHelper;
use IlBronza\Timings\Interfaces\HasTimingInterface;
use IlBronza\Timings\Traits\InteractsWithTimingTrait;
use IlBronza\Warehouse\Helpers\Unitloads\UnitloadCreatorHelper;
use IlBronza\Warehouse\Models\Interfaces\DeliverableInterface;
use IlBronza\Warehouse\Models\Interfaces\UnitloadProducibleInterface;
use Illuminate\Support\Collection;

use function strpos;

class OrderProductPhase extends ProductPackageBaseModel implements HasTimingInterface, UnitloadProducibleInterface
{
	static $modelConfigPrefix = 'orderProductPhase';
	public $classnameAbbreviation = 'opp';

	use InteractsWithTimingTrait;

	use OrderProductPhaseScopesTrait;
	use OrderProductPhaseRelationshipsTrait;
	use OrderProductPhaseProcessingsTrait;
	use OrderProductPhaseCheckerTrait;
	use OrderProductPhaseGetterTrait;
	use OrderProductPhaseButtonsAndRouting;

	use ProductAssignmentTrait;
	use CompletionScopesTrait;

	protected $touches = ['orderProduct'];

	public function getIndexUrl(array $data = [])
	{
		return IbRouter::route(app('products'), 'orderProductPhases.byOrderProduct', ['orderProduct' => $this->getOrderProductId()]);
	}

	public function getTimingChildren() : Collection
	{
		return collect();
	}

	public function getQuantityRequired() : ?float
	{
		return $this->quantity_required;
	}

	public function getAvailablePieces() : ? float
	{
		// dd($this->getPreviousOrderProductPhase());
		if($this->isFirst())
			return $this->getStock();

		if(! $previous = $this->getPreviousOrderProductPhase())
			return -1;

		return $previous->getPiecesDone();
	}

	public function getQuantityDone() : ?float
	{
		return $this->quantity_done;
	}

	public function getPiecesDoneByProcessings()
	{
		return $this->processings->sum('valid_pieces_done');
	}

	public function getTimingFather() : ?HasTimingInterface
	{
		return $this->getOrderProduct();
	}

	public function isLast()
	{
		$last = $this->getLastOrderProductPhase();

		return $this->is($last);
	}

	public function getWorkstationId()
	{
		if ($this->workstation_overridden_id)
			return $this->workstation_overridden_id;

		if (! $phase = $this->getPhase())
		{
			if ($phase = $this->phase()->withTrashed()->first())
			{
				$phase->restore();

				return $phase->getWorkstationId();
			}

			return null;
		}

		return $phase->getWorkstationId();
	}

	public function checkForQuantityDoneSyncing()
	{
		if ($this->isLast())
			$this->getOrderProduct()->setQuantityDone(
				$this->getQuantityDone(), $save = true
			);
	}

	public function getCalculatedWorkstationIdAttribute()
	{
		return $this->getWorkstationId();
	}

	public function setQuantityDone(float $quantityDone = null, bool $save = false)
	{
		// // leggo lo stack delle chiamate
		// $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

		// // il chiamante diretto è in $trace[1]
		// $caller = $trace[1]['class'] ?? null;

		// if (strpos($caller, 'OrderProductPhaseQuantityHelper') === false)
		// 	throw new \Exception('setQuantityDone() must be called only by OrderProductPhaseQuantityHelper. Called by: ' . ($caller ?? 'unknown'));

		$this->quantity_done = $quantityDone;

		if ($save)
			$this->save();
	}

	public function getSequence() : int
	{
		return $this->sequence ?? 0;
	}

	public function getOrderProductId() : string
	{
		return $this->order_product_id;
	}

	public function getCoefficientOutput() : ?float
	{
		if ($this->coefficient_output)
			return $this->coefficient_output;

		return $this->getPhase()?->getCoefficientOutput();
	}

	public function setCoefficientOutput(float $value = null, bool $save = false)
	{
		$this->_customSetter('coefficient_output', $value, $save);
	}

	public function setOrderProductId(string $value, bool $save = false)
	{
		$this->_customSetter('order_product_id', $value, $save);
	}

	public function setPhaseId(string $value, bool $save = false)
	{
		$this->_customSetter('phase_id', $value, $save);
	}

	public function setQuantityRequired(float $value = null, bool $save = false)
	{
		$this->_customSetter('quantity_required', $value, $save);
	}

	//DA SISTEMARE CON QUERY

	public function getOrderId()
	{
		return $this->getOrderProduct()->order_id;
	}

	public function __complete(Carbon $date)
	{
		$this->setCompletedAt($date);
		$this->setStatus('completed');

		$this->save();
	}

	public function _complete($lastCompletionProcessing = null)
	{
		$date = $lastCompletionProcessing ? $lastCompletionProcessing->getEndedAt() : Carbon::now();

		$this->__complete($date);
	}

	public function forceCompletion(string $processingType = 'production')
	{
		if (! $processing = $this->processings()->working()->byLast()->first())
		{
			$processingParameters = [
				'processing_type' => $processingType,
				'order_product_phase_id' => $this->getKey(),
				'started_at' => Carbon::now(),
				'ended_at' => Carbon::now(),
				'workstation_alias' => $this->getWorkstationId(),
				'user_id' => Auth::id()
			];

			$processing = ProcessingCreatorHelper::createByParameters($processingParameters);
			$processing->terminate();
		}

		UnitloadCreatorHelper::provideByModelsQuantity(
			$this->getProduct(),
			$this,
			$this->getQuantityRequired(),
			[
				'placeholder' => false
			],
			$processing
		);

		if (! $processing->getEndedAt())
			$processing->setEndedAt(
				Carbon::now()
			);

		$processing->setAsDefinitive(true);

//		OrderProductPhaseCompletionHelper::gpc()::complete($this);
		OrderProductPhaseCompletionHelper::gpc()::execute($this);
	}

	public function getContentForDelivery() : ? DeliverableInterface
	{
		return $this->getOrderProduct();
	}
}