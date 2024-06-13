<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class SellableRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [];

		if($target = $this->getModel()->getTarget())
			$relations['target'] = config("{$target->getPackageConfigPrefix()}.models.{$target->getModelConfigPrefix()}.controllers.show");

		return [
			'show' => [
				'relations' => $relations + [
					'quotations' => config('products.models.quotation.controllers.index'),
					'quotationrows' => config('products.models.quotationrow.controllers.index'),
					'sellableSuppliers' => [
						'controller' => config('products.models.sellableSupplier.controllers.index'),
						'elementGetterMethod' => 'getFullrelatedSellableSupplierElements'
					],
					'suppliers' => [
						'controller' => config('products.models.supplier.controllers.index'),
						'elementGetterMethod' => 'getFullrelatedSupplierElements'
					],
					// 'dossiers' => config('filecabinet.models.dossier.controllers.index'),
				]
			]
		];
	}
}