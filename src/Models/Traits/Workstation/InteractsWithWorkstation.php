<?php

namespace IlBronza\Products\Models\Traits\Workstation;

use IlBronza\Products\Models\Workstation;

trait InteractsWithWorkstation
{
	public function getWorkstationId()
	{
		return $this->workstation_id;
	}

	public function getWorkstation()
	{
		return $this->workstation()->first();
	}

	public function workstation()
	{
		return $this->belongsTo(
			Workstation::gpc()
		);
	}

	public function setWorkstationId(string $value, bool $save = false)
	{
		$this->_customSetter('workstation_id', $value, $save);
	}

}

