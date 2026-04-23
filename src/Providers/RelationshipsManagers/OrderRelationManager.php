<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Providers\Helpers\QuotationOrder\RowscontainerRelationsManagerParametersHelper;
use function config;
use function trans;

class OrderRelationManager Extends RelationshipsManager
{
	public function getAllRelationsParameters() : array
	{
		$result = [
			'show' => [
				'relations' => [

					'productRows' => RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'productRows'),

					'vehicleRows' => RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'vehicleRows'),

					'operatorRows' => RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'operatorRows')

				]
			]
		];

		if(! Order::gpc()::canHaveChildren())
		{
			unset($result['show']['relations']['parent']);
			unset($result['show']['relations']['children']);
		}

		return $result;
	}
}