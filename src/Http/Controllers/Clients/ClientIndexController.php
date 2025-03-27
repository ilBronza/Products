<?php

namespace IlBronza\Products\Http\Controllers\Clients;

use IlBronza\Category\Models\Category;
use IlBronza\Clients\Http\Controllers\Clients\ClientIndexController as BaseClientIndexController;

use function ini_set;

class ClientIndexController extends BaseClientIndexController
{
	public function getIndexElements()
	{
		$category = Category::gpc()::where('name', 'cliente')->first();

		$query = $this->getModelClass()::with([
			'categories',
			'destinations',
			'referents',
			'contacts',
			'address'
		])->byGeneralCategory($category);

		return $query->get();
	}
}