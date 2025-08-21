<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDCacheTrait;
use IlBronza\CRUD\Traits\Model\CRUDManyToManyTreeRelationalModelTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\MeasurementUnits\Traits\InteractsWithMeasurementUnit;
use IlBronza\Products\Models\Product\Product;

class ProductRelation extends BasePivotModel
{
	use CRUDUseUuidTrait;
	use PackagedModelsTrait;
	use InteractsWithMeasurementUnit;

	static $packageConfigPrefix = 'products';
	static $modelConfigPrefix = 'productRelation';

	use CRUDManyToManyTreeRelationalModelTrait;

	use CRUDCacheTrait;

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
		if($this->exists === false)
			return trans('products::productRelation.createRelation');

		$name = $this->getParent()->getName() . ' -> componente ' . $this->getComponent()->getName();

		if ($this->getMainCode())
			return $name . ' per ' . $this->getMainCode();

		return $name;
	}
}
