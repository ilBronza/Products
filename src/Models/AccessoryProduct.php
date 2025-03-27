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
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoryProduct extends BasePivotModel
{
	use SoftDeletes;

	use CRUDCacheTrait;
	use CRUDRelationshipModelTrait;
	use CRUDUseUuidTrait;

	static $modelConfigPrefix = 'accessoryProduct';
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
		'deleted_at' => 'datetime'
	];

	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

	public function phase()
	{
		return $this->belongsTo(Phase::getProjectClassName());
	}

	public function getPossiblePhasesValuesArray() : array
	{
		return $this->getPossiblePhases()->pluck('name', 'id')->toArray();
	}

	public function getPossiblePhases()
	{
		return $this->getProduct()->getPhases();
	}

	public function getProduct() : Product
	{
		return $this->getOrFindCachedRelatedElement('product');
	}

	public function accessory()
	{
		return $this->belongsTo(Accessory::getProjectClassName());
	}
}
