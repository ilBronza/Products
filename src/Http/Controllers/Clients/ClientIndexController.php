<?php

namespace IlBronza\Products\Http\Controllers\Clients;

use IlBronza\Category\Models\Category;
use IlBronza\Clients\Http\Controllers\Clients\ClientIndexController as BaseClientIndexController;

use function ini_set;

class ClientIndexController extends BaseClientIndexController
{
	public function getIndexElements()
	{
		ini_set('max_execution_time', '120');
		ini_set('memory_limit', '-1');

		$category = Category::gpc()::where('name', 'cliente')->first();

		$query = $this->getModelClass()::with([
			'categories',
			'destinations',
			'referents'
		])->byGeneralCategory($category);

		return $query->get();
	}


}