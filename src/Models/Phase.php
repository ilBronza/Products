<?php

namespace IlBronza\Products\Models;

use IlBronza\Buttons\Button;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Products\Models\Traits\Phase\PhaseRelationshipsTrait;


class Phase extends ProductPackageBaseModel
{
	use CRUDSluggableTrait;
	use CRUDParentingTrait;
	use PhaseRelationshipsTrait;

	static $modelConfigPrefix = 'phase';

	public function getProductKey()
	{
		return $this->product_id;
	}

	public function getIndexUrl(array $data = [])
	{
		return false;
	}

	static function getReorderButtonByProduct(Product $product) : Button
	{
		return Button::create([
			'href' => route(static::make()->getRouteBaseNamePrefix() . static::getPluralCamelcaseClassBasename() . '.reorder', ['product' => $product]),
			'text' => 'generals.reorder' . class_basename(static::class),
			'icon' => 'bars-staggered'
		]);
	}

}