<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDCacheTrait;
use IlBronza\CRUD\Traits\Model\CRUDModelTrait;
use IlBronza\CRUD\Traits\Model\CRUDRelationshipModelTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoryTypeProduct extends BasePivotModel
{
	use SoftDeletes;

	use CRUDCacheTrait;
	use CRUDRelationshipModelTrait;
	use CRUDUseUuidTrait;

	static $modelConfigPrefix = 'accessoryTypeProduct';

	use CRUDModelTrait;
	use ProductPackageBaseModelTrait;
	use PackagedModelsTrait
	{
		PackagedModelsTrait::getRouteBaseNamePrefix insteadof CRUDModelTrait;
		PackagedModelsTrait::getTranslatedClassname insteadof CRUDModelTrait;
		PackagedModelsTrait::getPluralTranslatedClassname insteadof CRUDModelTrait;
	}

	static $packageConfigPrefix = 'products';

	public $incrementing = true;
	protected $keyType = 'string';

	protected $casts = [
		'deleted_at' => 'datetime',
	];

	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

	public function accessoryType()
	{
		return $this->belongsTo(AccessoryType::getProjectClassName());
	}
}

