<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class SupplierRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [
		];

		// if($target = $this->getModel()->getTarget())
		// 	$relations['target'] = config("{$target->getPackageConfigPrefix()}.models.{$target->getModelConfigPrefix()}.controllers.show");


		// $relations['sellables'] = config('products.models.sellable.controllers.index');
		// $relations['sellableSuppliers'] = config('products.models.sellableSupplier.controllers.index');

		$relations['quotationrows'] = [
			'controller' =>  config('products.models.quotationrow.controllers.index'),
			'elementGetterMethod' => 'getRelatedQuotationrows'
		];







		return [
			'show' => [
				'relations' => $relations
			]
		];

		// return [
		// 	'show' => [
		// 		'relations' => [
		// 			// 'quotations' => config('products.models.quotation.controllers.index'),
		// 			// 'quotationrows' => config('products.models.quotationrow.controllers.index'),
		// 			// 'suppliers' => config('products.models.supplier.controllers.index'),
		// 			// 'dossiers' => config('filecabinet.models.dossier.controllers.index'),
		// 		]
		// 	]
		// ];
	}
}