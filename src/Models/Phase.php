<?php

namespace IlBronza\Products\Models;

use IlBronza\Buttons\Button;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Traits\Phase\PhaseRelationshipsTrait;
use IlBronza\Products\Models\Traits\Phase\PhaseScopesTrait;
use IlBronza\Products\Models\Traits\Workstation\InteractsWithWorkstation;

class Phase extends ProductPackageBaseModel
{
	use CRUDSluggableTrait;
	use CRUDParentingTrait;

	use PhaseRelationshipsTrait;
	use PhaseScopesTrait;

	use InteractsWithWorkstation;

	static $deletingRelationships = [
		'orderProductPhases'
	];

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

	public function setCoefficientOutput(float $value = null, bool $save = false)
	{
		$this->_customSetter('coefficient_output', $value, $save);
	}

	public function setProductId(string $value, bool $save = false)
	{
		$this->_customSetter('product_id', $value, $save);
	}

	public function setName(string $value, bool $save = false)
	{
		$this->_customSetter('name', $value, $save);
	}

	public function getCoefficientOutput() : ? float
	{
		return $this->coefficient_output;
	}

}