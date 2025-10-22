<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class AccessoryRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'children' => [
						'controller' => config('products.models.accessory.controllers.index'),
						'hasCreateButton' => true,
					]
				]
			]
		];
	}
}