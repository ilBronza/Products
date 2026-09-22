<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\Notes\Http\Controllers\CrudNoteController;

class CateringProductRelationManager extends ProductRelationManager
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
					'allergens' => [
						'controller' => config('products.models.allergen.controllers.index'),
						'hasAssociateButton' => true,						
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
					'orderProducts' => config('products.models.orderProduct.controllers.byProductIndex'),
				]
			]
		];
	}
}
