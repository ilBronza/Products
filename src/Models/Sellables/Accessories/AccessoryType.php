<?php

namespace IlBronza\Products\Models\Sellables\Accessories;

use IlBronza\Clients\Models\Client;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\HasCustomPricesTrait;
use IlBronza\Products\Models\AccessoryType as IbAccessoryType;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use Illuminate\Support\Collection;

class AccessoryType extends IbAccessoryType implements SellableItemInterface, WithPriceInterface
{
	use HasCustomPricesTrait;
	use InteractsWithSellableTrait;

	static $deletingRelationships = ['sellables'];

	public function getPriceFieldsForSellable() : array
	{
		return [
			'cost_per_movimentation' => 'forfait',
			'cost_per_hour' => 'hour',
			'revenue_per_movimentation' => 'forfait',
			'revenue_per_hour' => 'hour',
		];
	}

	public function getPossibleSuppliersElements() : Collection
	{
		return cache()->remember(
			$this->cacheKey('getPossibleSuppliersElements'), 3600, function ()
		{
			$owner = Client::gpc()::getOwnerCompany();

			return collect([SupplierCreatorHelper::getOrCreateSupplierFromTarget($owner)]);
		}
		);
	}

	public function getPossibleSuppliers() : Collection
	{
		return collect([
			Supplier::gpc()::getOwnerSupplier()]
		);
	}

	public function mustAutomaticallyUpdatePricesBySellable() : bool
	{
		return true;
	}

	public function getRowFieldsToStore() : array
	{
		$result = [
			'stored_cost_per_movimentation' => 'cost_per_movimentation',
			'stored_cost_per_hour' => 'cost_per_hour',
			'stored_revenue_per_movimentation' => 'revenue_per_movimentation',
			'stored_revenue_per_hour' => 'revenue_per_hour',
		];

		return $result;
	}

	// public function getPriceCreator() : ?SellableSupplierPriceCreatorBaseClass
	// {
	// 	$class = config('accessorys.models.accessoryType.helpers.sellableSupplierPricesCreator');

	// 	throw new \Exception($class);

	// 	return $class ? new $class : new \IlBronza\Vehicles\Helpers\VehiclePricesCreatorHelper;
	// }

	/**
	 * SE NULL ignora
	 * SE FALSE NO
	 * SE TRUE SI'
	 **/
	public function mustAutomaticallyUpdatePrices() : ? bool
	{
		return true;
	}

	public function getSellableSupplierIndexRelations() : array
	{
		return [
			'prices',
			'supplier.target',
		];
	}

	public function getContainerModelRelatedTablesToRefresh() : array
	{
		return ['accessoryRows'];
	}

	public function getDependentSellables() : array
	{
		return [];
	}
}