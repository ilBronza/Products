<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

use function config;

class OrderRelationManager Extends RelationshipsManager
{

	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'operatorRows' => [
						'controller' => config('products.models.quotationrow.controllers.index'),
						'selectRowCheckboxes' => true,

						//OperatorOrderrowFieldsgroupsParametersTrait
						'fieldsGroupsParametersFile' => config('products.models.orderrow.fieldsGroupsFiles.operatorOrderrow'),
						'translatedTitle' => trans('products::models.operatorRows'),
						'buttonsMethods' => [
							'getAddOperatorButton',
						]
					],
					'children' => [
						'controller' => config('products.models.order.controllers.index'),
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
	}
}