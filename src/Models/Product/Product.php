<?php

namespace IlBronza\Products\Models\Product;

use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Media\InteractsWithMedia;
use IlBronza\CRUD\Traits\Model\CRUDManyToManyTreeTrait;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\ProductRelation;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\Product\ProductQueriesTrait;
use IlBronza\Products\Models\Traits\Product\ProductRelationshipsTrait;
use IlBronza\Products\Models\Traits\Product\ProductScopesTrait;
use IlBronza\Warehouse\Models\Interfaces\UnitloadableInterface;
use Spatie\MediaLibrary\HasMedia;

class Product extends ProductPackageBaseModel implements HasMedia, UnitloadableInterface
{
	static $modelConfigPrefix = 'product';

	use InteractsWithMedia;

	use CRUDSluggableTrait;
	use ProductRelationshipsTrait;
	use ProductScopesTrait;
	use ProductQueriesTrait;

	use CompletionScopesTrait;

	use CRUDManyToManyTreeTrait;

	static $deletingRelationships = [
		'orders',
		'orderProducts',
		'phases'
	];

	public function getManyToManyRelationClass() : string
	{
		return ProductRelation::gpc();
	}

	public function getChildrenCountAttribute()
	{
		return $this->getCachedCalculatedProperty(
			$name = 'children_count', function ()
		{
			return $this->products()->count();
		}
		);
	}

	public function getPhasesDescriptionStringAttribute()
	{
		return $this->getCachedCalculatedProperty(
			$name = 'phases_description_string', function ()
		{
			return $this->getPhases()->implode('name', " - ");
		}
		);
	}

	public function getShortDescription()
	{
		return $this->short_description;
	}

	public function setName(string $value, bool $save = false)
	{
		return $this->_customSetter('name', $value, $save);
	}

	public function setClientId(string $value, bool $save = false)
	{
		$this->_customSetter('client_id', $value, $save);
	}

	public function hasStencil() : bool
	{
		return ! ! $this->getStencil();
	}

	public function setStencilId(string $value = null, bool $save = false)
	{
		$this->_customSetter('stencil_id', $value, $save);
	}

	public function setShortDescription(string $value = null, bool $save = false)
	{
		$this->_customSetter('short_description', $value, $save);
	}

	public function getLiveMaxPackingLength() : ?float
	{
		if ($this->max_packing_length)
			return $this->max_packing_length;

		if ($max = $this->getClient()?->getMaxPackingLength())
			return $max;

		$pallettype = $this->getPallettype();

		return $pallettype->getMaxLength();
	}

	public function getLiveMaxPackingHeight() : float
	{
		if ($this->max_packing_height)
			return $this->max_packing_height;

		if ($max = $this->getClient()?->getMaxPackingHeight())
			return $max;

		$pallettype = $this->getPallettype();

		return $pallettype->getMaxHeight();
	}

	public function getLiveMaxPackingWidth() : float
	{
		if ($this->max_packing_width)
			return $this->max_packing_width;

		if ($max = $this->getClient()?->getMaxPackingWidth())
			return $max;

		$pallettype = $this->getPallettype();

		return $pallettype->getMaxWidth();
	}

	public function getQuantityPerUnitload() : ? float
	{
		if(! ($packing = $this->getPacking()))
			return false;

		if($quantity = $packing->getQuantityPerPacking())
			return $quantity;

		return 99999999;
	}

	public function getVolumeCubicMeters() : ? float
	{
		if(! $packing = $this->getPacking())
			dd('manca packing');

		if($volume = $packing->getVolumeCubicMeters())
			return $volume;

		return config('warehouse.models.unitload.baseUnitloadVolumeCubicMeters');
	}

	public function getVolumeMc() : ? float
	{
		return $this->getVolumeCubicMeters();
	}
}