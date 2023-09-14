<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseScopesTrait;
use Illuminate\Support\Facades\Log;

class OrderProductPhase extends ProductPackageBaseModel
{
	static $modelConfigPrefix = 'orderProductPhase';

	use OrderProductPhaseScopesTrait;
	use OrderProductPhaseRelationshipsTrait;

	use CompletionScopesTrait;

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

			Log::critical('Non trovo fase per orderProductPhase ' . $this->getKey());
			return null;
		}

		return $phase->getWorkstationId();
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

	public function setQuantityDone(float $quantityDone, bool $save = false)
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
}