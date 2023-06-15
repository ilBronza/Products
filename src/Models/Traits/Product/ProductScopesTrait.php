<?php

namespace IlBronza\Products\Models\Traits\Product;

use Carbon\Carbon;

trait ProductScopesTrait
{
	public function scopeCurrent($query)
	{
		$query->whereHas(
			'orderProducts',
			function($_query)
			{
				$_query->where('created_at', '>' , Carbon::now()->subYears(1));
			}
		);
	}

}