<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

class ProductRelationManager Extends RelationshipsManager
{
	public function getAllRelationsParameters()
	{
		return [
			'show' => [
				'relations' => [
					'notes' => CrudNoteController::class,
					'phases' => config('products.models.phase.controllers.productPhaseIndex'),
					'orders' => config('products.models.order.controllers.productOrderIndex')
				]
			]
		];
	}
}