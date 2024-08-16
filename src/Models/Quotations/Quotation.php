<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Clients\Models\Traits\InteractsWithClientsTrait;
use IlBronza\Clients\Models\Traits\InteractsWithDestinationTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

class Quotation extends BaseModel
{
	protected $casts = [
		'date' => 'date'
	];

	use CRUDUseUuidTrait;
	use InteractsWithPriceTrait;

    public function getPriceModelClassName() : string
    {
        return Price::getProjectClassName();
    }

	use InteractsWithFormTrait;
	use ProductPackageBaseModelTrait;
	use CRUDParentingTrait;

	use InteractsWithNotesTrait;
	use InteractsWithClientsTrait;
	use InteractsWithDestinationTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;

	static $modelConfigPrefix = 'quotation';
    protected $deletingRelationships = [];

    public function quotationrows()
    {
    	return $this->hasMany(Quotationrow::getProjectClassName());
    }

    public function project()
    {
    	return $this->belongsTo(Project::getProjectClassName());
    }
}