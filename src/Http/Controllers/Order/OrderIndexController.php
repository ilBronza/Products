<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

use function config;
use function ini_set;

class OrderIndexController extends OrderCRUD
{
	use CRUDPlainIndexTrait;
	use CRUDIndexTrait;

	public $allowedMethods = ['index'];

	public function getIndexFieldsArray()
	{
		//OrderFieldsGroupParametersFile
		return config('products.models.order.fieldsGroupsFiles.index')::getTracedFieldsGroup();
	}

	public function getRelatedFieldsArray()
	{
		//OrderFieldsGroupParametersFile
		return config('products.models.order.fieldsGroupsFiles.related')::getTracedFieldsGroup();
	}

	public function getIndexElements()
	{
		ini_set('max_execution_time', 300);
		ini_set('memory_limit', - 1);

		$query = $this->getModelClass()::with(
			'project', 'destination', 'parent', 'client', 'quotation',
		);

		if(method_exists($placeholder = $this->getModelClass()::make(), 'getExtraFieldsClass'))
			if($placeholder->getExtraFieldsClass())
				$query->with('extraFields');

		return $query->get();
	}

}
