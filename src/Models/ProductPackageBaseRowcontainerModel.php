<?php

namespace IlBronza\Products\Models;

class ProductPackageBaseRowcontainerModel extends ProductPackageBaseModel
{
	public function scopeOpened($query)
	{
		return $query->whereHas('extraFields', function($_query)
		{
			$_query->whereNull('status')
				->orWhere('status', 'opened');
		});
	}
}