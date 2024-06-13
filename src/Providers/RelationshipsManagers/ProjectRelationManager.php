<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class ProjectRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'quotations' => config('products.models.quotation.controllers.index'),
					// 'client' => config('clients.models.client.controller'),
					// 'notes' => CrudNoteController::class
				]
			]
		];
	}
}