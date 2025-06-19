<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use IlBronza\Notes\Http\Controllers\CrudNoteController;

use function config;

class ProjectRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'quotations' => config('products.models.quotation.controllers.index'),
					'orders' => config('products.models.order.controllers.index'),
					 'client' => config('clients.models.client.controller'),
					 'notes' => CrudNoteController::class
				]
			]
		];
	}
}