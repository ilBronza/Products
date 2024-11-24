<?php

namespace IlBronza\Products\Models\Quotations\Quotationrows;

use App\Models\ProjectSpecific\Contracttype;
use IlBronza\Products\Models\Quotations\Quotationrow;
use Illuminate\Database\Eloquent\Builder;

use function config;

class OperatorQuotationrow extends Quotationrow
{
	static function getClassname() : string
	{
		return config('products.models.operatorQuotationrow.class');
	}

	public static function booted() : void
	{
		static::addGlobalScope('operatorQuotationrow', function (Builder $builder)
		{
			$builder->bySellableTargetType(
				Contracttype::getProjectClassName()::make()->getMorphClass()
			);
		});
	}
}