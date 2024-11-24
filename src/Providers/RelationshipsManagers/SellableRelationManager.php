<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class SellableRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [];

		$relations['sellableSuppliers'] = [
			'controller' => config('products.models.sellableSupplier.controllers.index'),
			'elementGetterMethod' => 'getSellableSuppliers',
			'buttonsMethods' => [
				'getCreateSellableButton',
			],
		];

		if($target = $this->getModel()->getTarget())
			$relations['target'] = config("{$target->getPackageConfigPrefix()}.models.{$target->getModelConfigPrefix()}.controllers.show");

			$relations['quotations'] = config('products.models.quotation.controllers.index');

//			$relations['suppliers'] = [
//							'controller' => config('products.models.supplier.controllers.index'),
//							'elementGetterMethod' => 'getFullrelatedSupplierElements'
//						];

		return [
			'show' => [
				'relations' => $relations
			]
		];
	}
}