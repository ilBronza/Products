<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class QuotationRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'quotationrows' => config('products.models.quotationrow.controllers.index'),
					'project' => config('products.models.project.controllers.show'),
					'dossiers' => config('filecabinet.models.dossier.controllers.index'),
				]
			]
		];
	}
}