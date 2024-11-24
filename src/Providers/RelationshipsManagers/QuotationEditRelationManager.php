<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

use function config;

class QuotationEditRelationManager Extends RelationshipsManager
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
				]
			]
		];
	}
}