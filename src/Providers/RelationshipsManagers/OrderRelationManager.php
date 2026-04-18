<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Providers\Helpers\QuotationOrder\RowscontainerRelationsManagerParametersHelper;
use function config;
use function trans;

class OrderRelationManager Extends RelationshipsManager
{
	public function getAllRelationsParameters() : array
	{
		$result = [
			'show' => [
				'relations' => [
					// 'productOrderrows' => [
					// 	'controller' => config('products.models.orderrrow.controllers.index'),
					// 	'selectRowCheckboxes' => true,

					// 	//ProductRowsByContainerFieldsGroupParametersFile
					// 	//ProductOrderrowsByContainerFieldsGroupParametersFile
					// 	'fieldsGroupsParametersFile' => config('products.models.orderrow.fieldsGroupsFiles.productOrderrow'),
					// 	'translatedTitle' => trans('products::models.productOrderrows'),
					// 	'buttonsMethods' => [
					// 		'getAddRowButton',
					// 		'getAddRowTableButton',
					// 	]
					// ],
					// 'operatorRows' => [
					// 	'controller' => config('products.models.orderrrow.controllers.index'),
					// 	'selectRowCheckboxes' => true,

					// 	//OperatorRowsByContainerFieldsGroupParametersFile

					// 	'fieldsGroupsParametersFile' => config('products.models.orderrow.fieldsGroupsFiles.operatorOrderrow'),
					// 	'translatedTitle' => trans('products::models.operatorRows'),
					// 	'buttonsMethods' => [
					// 		'getAddRowButton',
					// 	]
					// ],

					'vehicleRows' => RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'vehicleRows')

				]
			]
		];

		if(! Order::gpc()::canHaveChildren())
		{
			unset($result['show']['relations']['parent']);
			unset($result['show']['relations']['children']);
		}

		return $result;
	}
}