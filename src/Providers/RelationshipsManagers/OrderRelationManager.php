<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

use IlBronza\Products\Models\Order;

use function config;
use function trans;

class OrderRelationManager Extends RelationshipsManager
{

	public  function getAllRelationsParameters() : array
	{
		$result = [
			'show' => [
				'relations' => [
					'operatorRows' => [
						'controller' => config('products.models.orderrrow.controllers.index'),
						'selectRowCheckboxes' => true,

						//OperatorRowsByContainerFieldsGroupParametersFile

						'fieldsGroupsParametersFile' => config('products.models.orderrow.fieldsGroupsFiles.operatorOrderrow'),
						'translatedTitle' => trans('products::models.operatorRows'),
						'buttonsMethods' => [
							'getAddRowButton',
						]
					],
					'productOrderrows' => [
						'controller' => config('products.models.orderrrow.controllers.index'),
						'selectRowCheckboxes' => true,

						//ProductRowsByContainerFieldsGroupParametersFile
						'fieldsGroupsParametersFile' => config('products.models.orderrow.fieldsGroupsFiles.productOrderrow'),
						'translatedTitle' => trans('products::models.productOrderrows'),
						'buttonsMethods' => [
							'getAddRowButton',
							'getAddRowTableButton',
						]
					],
					'parent' => [
						'controller' => config('products.models.order.controllers.show'),
						'translatedTitle' => trans('products::models.parentOrder'),
					],
					'children' => [
						'controller' => config('products.models.order.controllers.index'),
						'translatedTitle' => trans('products::models.childrenOrders'),

						//OrderChildrenFieldsGroupParametersFile
						'fieldsGroupsParametersFile' => config('products.models.order.fieldsGroupsFiles.children'),

						// 'buttonsMethods' => [
						// 	'getAddChildrenButton',
						// ],
					],
					'orderProducts' => config('products.models.orderProduct.controllers.byOrderIndex'),
					'notes' => CrudNoteController::class,
					// 'phases' => [
					// 	'controller' => config('products.models.phase.controllers.productPhaseIndex'),
					// 	'selectRowCheckboxes' => false,
					// 	'buttonsMethods' => [
					// 		'getReorderButtonByProduct'
					// 	],
					// ],
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