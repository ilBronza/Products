<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

class OrderProductRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
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
					'product' => config('products.models.product.controllers.teaser'),
					'order' => config('products.models.order.controllers.teaser'),
				]
			]
		];
	}
}