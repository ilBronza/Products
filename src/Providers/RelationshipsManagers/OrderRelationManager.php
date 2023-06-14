<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

class OrderRelationManager Extends RelationshipsManager
{
	public function getAllRelationsParameters()
	{
		return [
			'show' => [
				'relations' => [
					'notes' => CrudNoteController::class,
					// 'phases' => [
					// 	'controller' => config('products.models.phase.controllers.productPhaseIndex'),
					// 	'selectRowCheckboxes' => false,
					// 	'buttonsMethods' => [
					// 		'getReorderButtonByProduct'
					// 	],
					// ],
					'orderProducts' => config('products.models.orderProduct.controllers.byOrderIndex')
				]
			]
		];
	}
}