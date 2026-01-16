<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Orderrows\VehicleOrderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

trait UsesVehicleOrderrowTrait
{
	public function vehicleOrderrows()
	{
		return $this->hasMany(VehicleOrderrow::gpc());
	}

	public function getAddProductUrl() : string
	{
		return $this->getAddRowByTypeUrl('Vehicle');
	}

}