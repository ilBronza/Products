<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;

class SellableSupplierRelationManager Extends RelationshipsManager
{
	public  function getAllRelationsParameters() : array
	{
		return [
			'show' => [
				'relations' => [
					// 'quotations' => config('products.models.quotation.controllers.index'),
					// 'quotationrows' => config('products.models.quotationrow.controllers.index'),
					// 'suppliers' => config('products.models.supplier.controllers.index'),
					// 'dossiers' => config('filecabinet.models.dossier.controllers.index'),
				]
			]
		];
	}
}