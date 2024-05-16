<?php

namespace IlBronza\Products\Models;

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
use IlBronza\Ukn\Facades\Ukn;
use Illuminate\Support\Facades\Log;

class OrderProductPhase extends ProductPackageBaseModel
{
	public $classnameAbbreviation = 'opp';
	static $modelConfigPrefix = 'orderProductPhase';

	use OrderProductPhaseScopesTrait;
	use OrderProductPhaseRelationshipsTrait;
	use OrderProductPhaseProcessingsTrait;
	use OrderProductPhaseCheckerTrait;
	use OrderProductPhaseGetterTrait;
	use OrderProductPhaseButtonsAndRouting;

	use ProductAssignmentTrait;
	use CompletionScopesTrait;

	public function isLast()
	{
		$last = $this->getLastOrderProductPhase();

		return $this->is($last);
	}

	public function getWorkstationId()
	{
		if($this->workstation_overridden_id)
			return $this->workstation_overridden_id;

		if(! $phase = $this->getPhase())
		{
			if($phase = $this->phase()->withTrashed()->first())
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
		if($this->isLast())
			$this->getOrderProduct()->setQuantityDone(
				$this->getQuantityDone(),
				$save = true
			);
	}

	protected static function boot()
	{
		parent::boot();
	
		static::saved(function ($model)
		{
			if($model->isDirty('quantity_done'))
				$model->checkForQuantityDoneSyncing();
		});
	}

	public function getCalculatedWorkstationIdAttribute()
	{
		return $this->getWorkstationId();
	}

	public function getQuantityRequired()
	{
		return $this->quantity_required;
	}

	public function getQuantityDone()
	{
		return $this->quantity_done;
	}

	public function setQuantityDone(float $quantityDone = null, bool $save = false)
	{
		$this->quantity_done = $quantityDone;

		if($save)
			$this->save();
	}

	public function getSequence() : int
	{
		return $this->sequence ?? 0;
	}

	public function getIndexUrl(array $data = [])
	{
        return IbRouter::route(app('products'), 'orderProductPhases.byOrderProduct', ['orderProduct' => $this->getOrderProductId()]);
	}

	public function getOrderProductId() : string
	{
		return $this->order_product_id;
	}

	public function getCoefficientOutput() : ? float
	{
		if($this->coefficient_output)
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

	public function checkCompletion()
	{
		if(! $this->processings()->forCompletion()->definitive()->byLast()->first())
			return $this->uncomplete();

		return $this->complete();
	}

	public function bindDataFromProcessings()
	{
		$processings = $this->processings()
						->forCompletion()
						->byLast()
						->get();

		$this->setStartedAt($processings->min('created_at') ?? null);

		$this->setQuantityDone(
			$processings->sum('valid_pieces_done')
		);
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

	public function complete()
	{
		$this->bindDataFromProcessings();

		if(! $lastCompletionProcessing = $this->processings()
						->forCompletion()
						->definitive()
						->byLast()
						->first())
			throw new \Exception('non trovato il processo che termina la lavorazione ' . $this->getName() . ' <a href="' . $this->getShowUrl() . '">Controlla qui</a>');

		$this->_complete($lastCompletionProcessing);
	}

	public function forceUncomplete()
	{
		$this->processings()->delete();

		$this->uncomplete();
	}

	public function uncomplete()
	{
		$this->bindDataFromProcessings();

		$this->setCompletedAt(null);
		$this->setStatus('waiting');

		$this->timing()->forceDelete();

		$this->save();
	}

    public function forceCompletion()
    {
		if(! $processing = $this->processings()->working()->byLast()->first())
		{
			$processing = $this->_createAndAssociateProcessing([
				'processing_type' => 'production'
			]);

			$processing->setValidPiecesDone(
				$this->getQuantityRequired() - $this->getQuantityDone()
			);
		}

		if(! $processing->getEndedAt())
			$processing->setEndedAt(
				Carbon::now()
			);

		$processing->setAsDefinitive(true);
    }


}