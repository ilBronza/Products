<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\Phase;

trait OrderProductPhaseRelationshipsTrait
{
	public function orderProduct()
	{
		return $this->belongsTo(OrderProduct::getProjectClassName());
	}

	public function phase()
	{
		return $this->belongsTo(Phase::getProjectClassName());
	}
}