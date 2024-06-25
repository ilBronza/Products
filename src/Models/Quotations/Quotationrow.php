<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

class Quotationrow extends BaseModel
{
    use CRUDUseUuidTrait;
    use InteractsWithPriceTrait;
    use InteractsWithFormTrait;
	use ProductPackageBaseModelTrait;
	use InteractsWithNotesTrait;
    use CRUDParentingTrait;

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date'
    ];

	static $modelConfigPrefix = 'quotationrow';
    protected $deletingRelationships = [];

    protected $with = ['sellable'];

    public function getPriceModelClassName() : string
    {
        return Price::getProjectClassName();
    }

    public function sellable()
    {
        return $this->belongsTo(Sellable::getProjectClassname());
    }

    public function getSellable() : ? Sellable
    {
        if($this->relationLoaded('sellable'))
            return $this->sellable;

        return $this->sellable()->first();
    }

    public function sellableSupplier()
    {
        return $this->belongsTo(SellableSupplier::getProjectClassname());
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::getProjectClassname());
    }

    public function getName() : ? string
    {
        return $this->getSellable()?->getName();
    }
}