<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\Traits\Orderrow\OrderrowRelationsScopesTrait;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

class Orderrow extends BaseModel
{
	use CRUDParentingTrait;
	use ProductPackageBaseModelTrait;
	use OrderrowRelationsScopesTrait;
	use InteractsWithPriceTrait;
    use InteractsWithFormTrait;

    public function getPriceModelClassName() : string
    {
        return Price::getProjectClassName();
    }

	static $modelConfigPrefix = 'orderrow';
	public $classnameAbbreviation = 'or';
}