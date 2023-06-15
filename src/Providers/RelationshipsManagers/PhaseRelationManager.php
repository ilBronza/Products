<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;

class PhaseRelationManager Extends RelationshipsManager
{
	public function getAllRelationsParameters()
	{
		return [
			'show' => [
				'relations' => [
					'notes' => CrudNoteController::class,
					'product' => config('products.models.product.controllers.teaser'),
					'orderProductPhases' => [
						'controller' => config('products.models.orderProductPhase.controllers.phaseOrderProductPhaseIndex'),
						'selectRowCheckboxes' => false,
					],
				]
			]
		];
	}
}