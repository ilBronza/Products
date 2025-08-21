<?php

namespace IlBronza\Products\Http\Controllers\Clients;

use IlBronza\Category\Models\Category;
use IlBronza\Clients\Http\Controllers\Clients\ClientIndexController as BaseClientIndexController;

use function ini_set;

class ClientIndexController extends BaseClientIndexController
{
	public $avoidCreateButton = true;

	public function getIndexElements()
	{
		$query = $this->getModelClass()::asClient()->with([
			'categories',
			'destinations',
			'referents',
			'contacts',
			'address'
		]);

		return $query->get();
	}
}