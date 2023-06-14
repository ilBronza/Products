<?php

namespace IlBronza\Products\Models\Traits\Product;

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