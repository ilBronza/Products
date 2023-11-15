<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

class ProductRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'productRelations' => config('products.models.productRelation.controllers.byProductIndex'),
					'accessoryProducts' => config('products.models.accessoryProduct.controllers.byProductIndex'),
					'phases' => [
						'controller' => config('products.models.phase.controllers.productPhaseIndex'),
						'selectRowCheckboxes' => false,
						'buttonsMethods' => [
							'getReorderButtonByProduct'
						],
					],
					'notes' => CrudNoteController::class,
					'orderProducts' => config('products.models.orderProduct.controllers.byProductIndex'),
				]
			]
		];
	}
}