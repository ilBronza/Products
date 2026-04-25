<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSupplierTrait;
use IlBronza\Products\Providers\Helpers\Suppliers\SupplierRelationManagerParametersHelper;
// use IlBronza\Products\Providers\Helpers\SellableSuppliers\SellableSupplierManagerParametersHelper;

class AccessoryRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [];

		if(in_array(InteractsWithSupplierTrait::class, class_uses_recursive($this->getModel())))
			$relations = array_merge($relations, SupplierRelationManagerParametersHelper::getSupplierRelationManagerParameters($this->getModel()));

		$relation['products'] = [
			'controller' => config('products.models.product.controllers.index'),
		];

		$relations['children'] = [
						'controller' => config('products.models.accessory.controllers.index'),
					];

		$relations['parent'] = [
						'controller' => config('products.models.accessory.controllers.show')
					];

		return [
			'show' => [
				'relations' => $relations
			]
		];
	}
}