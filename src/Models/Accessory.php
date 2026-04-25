<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Media\InteractsWithMedia;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Traits\Accessory\AccessoryRelationshipsScopesTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;

class Accessory extends ProductPackageBaseModel implements HasMedia
{
    use CRUDParentingTrait;
    use CRUDSluggableTrait;
    use InteractsWithMedia;
	use AccessoryRelationshipsScopesTrait;

	static $modelConfigPrefix = 'accessory';
    static $deletingRelationships = ['media'];

    static function getPossibleAccessoriesByProduct(Product $product) : Collection
    {
        return static::getProjectClassName()::all();
    }

    static function getPossibleAccessoriesSelectListByProduct(Product $product) : Collection
    {
        $elements = static::getPossibleAccessoriesByProduct($product);

        return $elements->pluck('name', 'id');
    }

}