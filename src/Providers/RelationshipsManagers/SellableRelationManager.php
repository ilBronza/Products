<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use App\Http\Controllers\PackageOverriding\Parameters\FieldsGroups\OperatorQuotationrowByQuotationFieldsGroupParametersFile;
use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class SellableRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		$relations = [];

		$sellable = $this->getModel();

		//SellableSupplierIndexController
		$relations['sellableSuppliers'] = [
			'controller' => config('products.models.sellableSupplier.controllers.index'),
			'elementGetterMethod' => 'getSellableSuppliersRelatedElements',
			'fieldsGroupsParametersFile' => config('products.models.sellableSupplier.fieldsGroupsFiles.relatedByType.' . $sellable->type),
			'buttonsMethods' => [
				'getCreateSellableButton',
			],
		];

//		if($target = $this->getModel()->getTarget())
//			$relations['target'] = config("{$target->getPackageConfigPrefix()}.models.{$target->getModelConfigPrefix()}.controllers.show");

		//QuotationIndexController
		$relations['quotations'] = [
			'controller' => config('products.models.quotation.controllers.index'),
			'elementGetterMethod' => 'getQuotationsRelatedElements',
		];

		//OrderIndexController
		$relations['orders'] = [
			'controller' => config('products.models.order.controllers.index'),
			'elementGetterMethod' => 'getOrdersRelatedElements',
		];

		return [
			'show' => [
				'relations' => $relations
			]
		];
	}
}