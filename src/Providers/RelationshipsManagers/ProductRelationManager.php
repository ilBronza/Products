<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

use function config;

class ProductRelationManager extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'productRelations' => [
						'controller' => config('products.models.productRelation.controllers.byProductIndex'),
						'hasCreateButton' => true,
					],
					'accessoryProducts' => config('products.models.accessoryProduct.controllers.byProductIndex'),
					'phases' => [
						'controller' => config('products.models.phase.controllers.productPhaseIndex'),
						'selectRowCheckboxes' => false,
						'hasCreateButton' => true,
						'buttonsMethods' => [
							'getReorderButtonByProduct'
						],
					],
					'notes' => [
						'controller' => CrudNoteController::class,
						'hasCreateButton' => true,						
					],
					// 'orderProducts' => config('products.models.orderProduct.controllers.byProductIndex'),
				]
			]
		];
	}
}

//getAddNotesButton