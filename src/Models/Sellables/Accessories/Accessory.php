<?php

namespace IlBronza\Products\Models\Sellables\Accessories;

use IlBronza\Products\Models\Accessory as IbAccessory;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSupplierTrait;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use Illuminate\Support\Collection;

class Accessory extends IbAccessory implements SupplierInterface
{
	use InteractsWithSupplierTrait;

	public function getPriceFieldsForSellable() : array
	{
		return [
			'cost_per_movimentation' => 'piece',
			'cost_per_hour' => 'piece',
			'revenue_per_movimentation' => 'piece',
			'revenue_per_hour' => 'piece',
		];
	}

	public function mustAutomaticallyUpdatePrices() : ? bool
	{
		return true;
	}

	public function getPossibleSellables() : Collection
	{
		if(! $accessoryType = $this->getaccessoryType())
			return collect();
		
		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget($accessoryType);

		return collect([$sellable]);
	}

}