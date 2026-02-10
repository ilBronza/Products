<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;
use function config;

class QuotationRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					'quotationrows' => [
 						'controller' => config('products.models.quotationrow.controllers.index'),
//						'elementGetterMethod' => 'getQuotationrowsForShowRelation',
						'fieldsGroupsParametersFile' => config('products.models.quotationrow.fieldsGroupsFiles.byQuotation')
					],
					'project' => config('products.models.project.controllers.show'),
//					'dossiers' => config('filecabinet.models.dossier.controllers.index'),
					'notes' => CrudNoteController::class,
				]
			]
		];
	}
}