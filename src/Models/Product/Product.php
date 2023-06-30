<?php

namespace IlBronza\Products\Models\Product;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDManyToManyTreeTrait;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\ProductRelation;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\Product\ProductQueriesTrait;
use IlBronza\Products\Models\Traits\Product\ProductRelationshipsTrait;
use IlBronza\Products\Models\Traits\Product\ProductScopesTrait;

class Product extends ProductPackageBaseModel
{
	static $modelConfigPrefix = 'product';

	use CRUDSluggableTrait;
	use ProductRelationshipsTrait;
	use ProductScopesTrait;
	use ProductQueriesTrait;

	use CompletionScopesTrait;

	use CRUDManyToManyTreeTrait;

	public function getManyToManyRelationClass() : string
	{
		return ProductRelation::class;
	}

	public function getChildrenCountAttribute()
	{
		return $this->getCachedCalculatedProperty(
			$name = 'children_count',
			function()
			{
				return $this->products()->count();
			}
		);
	}

	public function getPhasesDescriptionStringAttribute()
	{
		return $this->getCachedCalculatedProperty(
			$name = 'phases_description_string',
			function()
			{
				return $this->getPhases()->implode('name', " - ");
			}
		);		
	}

	public function getShortDescription()
	{
		return $this->short_description;
	}

}