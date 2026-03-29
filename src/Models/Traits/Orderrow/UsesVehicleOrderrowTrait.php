<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Orderrows\VehicleOrderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

trait UsesVehicleOrderrowTrait
{
	public function vehicleRows()
	{
		return $this->vehicleOrderrows();
	}

	public function vehicleOrderrows()
	{
		return $this->hasMany(VehicleOrderrow::gpc());
	}

	public function getVehicleRows()
	{
		return $this->vehicleOrderrows;
	}

	public function getAddVehicleTypeUrl() : string
	{
		return $this->getAddRowByTypeUrl('VehicleType');
	}

}