<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

use IlBronza\Products\Models\Product\Product;

trait OrderProductGetterSetterTrait
{
    public function getOrderId() : string
    {
        return $this->order_id;
    }

    public function getProductId() : string
    {
        return $this->product_id;
    }

    public function getProductName() : string
    {
        if($this->live_product_name)
            return $this->live_product_name;

        return $this->getProduct()->getName();
    }

    public function setQuantityDone(float $value = null, bool $save = false)
    {
        $this->_customSetter('quantity_done', $value, $save);
    }



}