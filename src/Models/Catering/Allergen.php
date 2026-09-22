<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\ProductPackageBaseModel;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Allergen extends ProductPackageBaseModel
{
	use CRUDSluggableTrait;

	static $modelConfigPrefix = 'allergen';
	static $deletingRelationships = [];

	public function renderText() : ?string
	{
		return $this->getName();
	}

	public function renderIcon(string $folder) : ?string
	{
		if (! $slug = $this->getSlug())
			return null;

		$folder = trim($folder, '/');

		if (! $folder || str_contains($folder, '..') || str_contains($folder, '\\'))
			return null;

		$iconPath = dirname(__DIR__, 3) . '/resources/views/catering/allergens/icons/' . $folder . '/' . $slug . '.svg';

		if (! is_file($iconPath))
			return null;

		return file_get_contents($iconPath) ?: null;
	}

	public function products() : MorphToMany
	{
		return $this->morphedByMany(
			Product::gpc(),
			'allergenable',
			config('products.models.allergenable.table')
		)->using(Allergenable::gpc())
			->withTimestamps();
	}
}
