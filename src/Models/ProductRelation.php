<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Traits\Model\CRUDCacheTrait;
use IlBronza\CRUD\Traits\Model\CRUDManyToManyTreeRelationalModelTrait;
use IlBronza\CRUD\Traits\Model\CRUDModelTrait;
use IlBronza\CRUD\Traits\Model\CRUDRelationshipModelTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRelation extends Pivot
{
	use CRUDManyToManyTreeRelationalModelTrait;

	use SoftDeletes;
	use CRUDCacheTrait;
	use CRUDRelationshipModelTrait;
	use CRUDUseUuidTrait;

	use CRUDModelTrait, ProductPackageBaseModelTrait;
	// {
	// 	ProductPackageBaseModelTrait::getRouteBaseNamePrefix insteadof CRUDModelTrait;
	// }

	protected $keyType = 'string';

	protected $casts = [
		'deleted_at' => 'datetime'
	];

	public $incrementing = true;

	protected $guarded = [];

	public function getTable() : string
	{
		return config("products.models.productRelation.table");
	}

	public function getRelatedClassName()
	{
		return Product::getProjectClassName();
	}

	public function getManyToManyRelationPivotFields()
	{
		return [
			'id',
			'main_code',
			'quantity_coefficient'
		];
	}

	public function component()
	{
		return $this->belongsTo(
			Product::getProjectClassName(), 'child_id'
		);
	}

	public function parent()
	{
		return $this->belongsTo(
			Product::getProjectClassName(), 'parent_id'
		);
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function getComponent()
	{
		return $this->component;
	}

	public function getMainCode()
	{
		return $this->main_code;
	}

	public function getName() : ?string
	{
		$name = $this->getParent()->getName() . ' -> componente ' . $this->getComponent()->getName();

		if ($this->getMainCode())
			return $name . ' per ' . $this->getMainCode();

		return $name;
	}
}
