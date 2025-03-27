<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\Media\InteractsWithMedia;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Products\Models\Interfaces\SizeInterface;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use IlBronza\Warehouse\Models\Pallettype\Pallettype;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;

class Packing extends ProductPackageBaseModel implements SizeInterface, HasMedia
{
    use InteractsWithMedia;
	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
	protected $keyType = 'string';

	static $modelConfigPrefix = 'packing';

	protected $casts = [
		'imported_at' => 'datetime',
		'calculated_at' => 'datetime',
		'verified_at' => 'datetime'
	];

	public function pallettype()
	{
		return $this->belongsTo(Pallettype::getProjectClassName());
	}

	public function getPallettype() : ? Pallettype
	{
		return $this->pallettype;
	}

	public function scopeVerified($query)
	{
		$query->whereNotNull('verified_at');
	}

	public function packable() : MorphTo
	{
		return $this->morphTo('packable');
	}

	public function getProduct() : Product
	{
		return $this->packable;
	}

	public function setQuantityPerPackage(float $value = null, bool $save = false)
	{
		return $this->_customSetter('quantity_per_package', $value, $save);
	}

	public function setPackageHeight(float $value = null, bool $save = false)
	{
		return $this->_customSetter('package_height', $value, $save);
	}

	public function setPackageLength(float $value = null, bool $save = false)
	{
		return $this->_customSetter('package_length', $value, $save);
	}

	public function setPackageWidth(float $value = null, bool $save = false)
	{
		return $this->_customSetter('package_width', $value, $save);
	}

	public function setPackageWeight(float $value = null, bool $save = false)
	{
		return $this->_customSetter('package_weight', $value, $save);
	}

	public function setPackingWeight(float $value = null, bool $save = false)
	{
		return $this->_customSetter('packing_weight', $value, $save);
	}

	public function setPackageVolumeMq(float $value = null, bool $save = false)
	{
		return $this->_customSetter('package_volume_mq', $value, $save);
	}

	public function setPackagePerLayer(float $value = null, bool $save = false)
	{
		return $this->_customSetter('package_per_layer', $value, $save);
	}

	public function setLayersPerPacking(float $value = null, bool $save = false)
	{
		return $this->_customSetter('layers_per_packing', $value, $save);
	}

	public function setQuantityPerPacking(float $value = null, bool $save = false)
	{
		return $this->_customSetter('quantity_per_packing', $value, $save);
	}

	public function setPackingLength(float $value = null, bool $save = false)
	{
		return $this->_customSetter('packing_length', $value, $save);
	}

	public function setPackingWidth(float $value = null, bool $save = false)
	{
		return $this->_customSetter('packing_width', $value, $save);
	}

	public function setPackingHeight(float $value = null, bool $save = false)
	{
		return $this->_customSetter('packing_height', $value, $save);
	}

	public function setPackingVolumeMq(float $value = null, bool $save = false)
	{
		return $this->_customSetter('packing_volume_mq', $value, $save);
	}

	public function setCalculatedAt(Carbon $value, bool $save = false)
	{
		return $this->_customSetter('calculated_at', $value, $save);
	}


	public function getPackingLength() : ? float
	{
		return $this->packing_length;
	}

	public function getPackingWidth() : ? float
	{
		return $this->packing_width;
	}

	public function getPackingHeight() : ? float
	{
		return $this->packing_height;
	}










	public function getQuantityPerPacking() : ? float
	{
		return $this->quantity_per_packing;
	}

	public function getQuantityPerPackage() : ? float
	{
		return $this->quantity_per_package;
	}

	public function getLayersPerPacking() : ? float
	{
		return $this->layers_per_packing;
	}

	public function getPackagePerLayer() : ? float
	{
		return $this->package_per_layer;
	}

	public function getPackageHeight() : ? float
	{
		return $this->package_height;
	}

	public function getPackageWidth() : ? float
	{
		return $this->package_width;
	}

	public function getPackageLength() : ? float
	{
		return $this->package_length;
	}

	public function getVerifiedAt() : ? Carbon
	{
		return $this->verified_at;
	}

	// public function getPackageWidth() : ? float
	// {
	// 	return $this->package_width;
	// }

	public function hasBeenVerified() : bool
	{
		return !! $this->getVerifiedAt();
	}

}