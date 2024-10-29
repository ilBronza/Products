<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class SupplierRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [
		];

		//SellableSupplierByOperatorFieldsGroupParametersFile
		$relations['sellableSuppliers'] = [
			'controller' => config('products.models.sellableSupplier.controllers.index'),
			'fieldsGroupsParametersFile' => config('products.models.sellableSupplier.fieldsGroupsFiles.byOperator')
		];

		$relations['quotationrows'] = [
			'controller' =>  config('products.models.quotationrow.controllers.index'),
			'elementGetterMethod' => 'getRelatedQuotationrows'
		];

		$relations['dossiers'] = [
			'controller' => config('filecabinet.models.dossier.controllers.index'),
			'buttonsMethods' => [
			]
		];

//		if($target = $this->getModel()->getTarget())
//			$relations['target'] = config("{$target->getPackageConfigPrefix()}.models.{$target->getModelConfigPrefix()}.controllers.show");
//

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