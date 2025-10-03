<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use Illuminate\Support\Collection;

class Workstation extends ProductPackageBaseModel
{
	use CRUDSluggableTrait;

	static $modelConfigPrefix = 'workstation';

	static public function buildElementsArryForSelect(Collection $elements) : array
	{
		$result = [];

		foreach ($elements as $element)
			$result[$element->getSlug()] = $element->getNameForDisplayRelation();

		return $result;
	}

}