<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use App\Models\ProjectSpecific\Supplier;
use IlBronza\Products\Models\Interfaces\SupplierInterface;

class SupplierCreatorHelper
{
    static function createSupplierFromTarget(SupplierInterface $target) : Supplier
    {
        $supplier = Supplier::getProjectClassName()::make();
        $supplier->target()->associate($target);
        $supplier->save();

        return $supplier;
    }


    static function getOrCreateSupplierFromTarget(SupplierInterface $target) : Supplier
    {
        if($supplier = $target->getSupplier())
            return $supplier;

        return static::createSupplierFromTarget($target);
    }
}