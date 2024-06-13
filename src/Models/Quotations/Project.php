<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Clients\Models\Traits\InteractsWithClientsTrait;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Quotations\Quotation;

class Project extends ProductPackageBaseModel
{
	use InteractsWithClientsTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;

	static $modelConfigPrefix = 'project';
    protected $deletingRelationships = [];

	protected $casts = [
		'started_at' => 'date',
		'completed_at' => 'date',
	];

	public function quotations()
	{
		return $this->hasMany(Quotation::getProjectClassname());
	}
}