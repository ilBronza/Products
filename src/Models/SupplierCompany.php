<?php

namespace IlBronza\Products\Models;

use IlBronza\Clients\Models\Client as IbClient;
use IlBronza\Products\Models\Interfaces\SupplierInterface;

class SupplierCompany extends IbClient implements SupplierInterface
{
	public static function boot()
	{
		parent::boot();
		// Will fire everytime an User is created
		static::created(function (SupplierCompany $supplierCompany)
		{
			dd($supplierCompany);
		});
	}

	public function getMorphClass()
	{
		return 'Client';
	}

	public function getForeignKey()
	{
		return 'client_id';
	}

}