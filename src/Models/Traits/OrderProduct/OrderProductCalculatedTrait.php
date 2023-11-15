<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

use IlBronza\Products\Models\Product\Product;

trait OrderProductCalculatedTrait
{
    public function getPiecesPerPacking() : ? float
    {
        return $this->getProduct()->getPiecesPerPacking();
    }

    public function getPackageNumber() : ? int
    {
        if(! $pieces = $this->getQuantityDone())
            return null;

        if(! $piecesPerPacking = $this->getPiecesPerPacking())
            return null;

        return ceil($pieces / $piecesPerPacking);
    }
}