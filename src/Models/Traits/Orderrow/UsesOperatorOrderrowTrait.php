<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Orderrows\OperatorOrderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

trait UsesOperatorOrderrowTrait
{
	public function operatorRows()
	{
		return $this->operatorOrderrows();
	}

	public function operatorOrderrows()
	{
		return $this->hasMany(OperatorOrderrow::gpc());
	}

	public function getAddOperatorUrl() : string
	{
		return $this->getAddRowByTypeUrl('Contracttype');
	}

	public function getPeopleDaysAttribute() : int
	{
		return $this->operatorOrderrows->sum('quantity');
	}

	public function getPeopleCountAttribute() : int
	{
		return $this->operatorOrderrows->unique('sellable_supplier_id')->count();
	}

	public function getActivitiesCountAttribute() : int
	{
		return $this->operatorOrderrows->unique('sellable_id')->count();
	}
}