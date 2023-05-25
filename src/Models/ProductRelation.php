<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Traits\Model\CRUDMAnyToManyTreeRelationalModelTrait;
use IlBronza\Products\Models\Product;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRelation extends Pivot
{
	use CRUDMAnyToManyTreeRelationalModelTrait;

	use SoftDeletes;

	protected $dates = [
		'deleted_at'
	];

	public $incrementing = true;
	protected $guarded = [];

	// protected $table = 'costi_component_relations';

	public function getRelatedClassName()
	{
		return Product::getProjectClassName();
	}

	public function getManyToManyRelationPivotFields() {
		return [
			'id',
			'main_code',
			'quantity_coefficient'
		];
	}
}
