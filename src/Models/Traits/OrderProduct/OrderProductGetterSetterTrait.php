<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

trait OrderProductGetterSetterTrait
{
	public function getProductKey() : ?string
	{
		return $this->product_id;
	}

	public function getQuantityRequired() : ?float
	{
		return $this->quantity_required;
	}

	public function getQuantityDone() : ?float
	{
		return $this->quantity_done;
	}

	public function getOrderId() : string
	{
		return $this->order_id;
	}

	public function getProductId() : string
	{
		return $this->product_id;
	}

	public function getName() : ?string
	{
		return "{$this->getProductName()}-{$this->getOrderName()}";
	}

	public function getOrderName() : ?string
	{
		return $this->getOrder()?->getName();
	}

	public function getProductName() : ?string
	{
		if ($this->live_product_name)
			return $this->live_product_name;

		return $this->getProduct()?->getName();
	}

	public function getTimingEstimator()
	{
		$class = config('products.models.orderProduct.timingEstimator');

		return new $class($this);
	}

	public function setQuantityDone(float $value = null, bool $save = false)
	{
		$this->_customSetter('quantity_done', $value, $save);
	}

	public function setQuantityRequired(float $value = null, bool $save = false)
	{
		$this->_customSetter('quantity_required', $value, $save);
	}

	public function setProductId(string $value, bool $save = false)
	{
		$this->_customSetter('product_id', $value, $save);
	}

	public function setOrderId(string $value, bool $save = false)
	{
		$this->_customSetter('order_id', $value, $save);
	}

	public function getQuantityDoneDiscrepancyAttribute() : ?int
	{
		if (! $this->isCompleted())
			return null;

		return $this->getQuantityDone() - $this->getQuantityRequired();
	}

	public function getQuantityDoneDiscrepancy() : ?int
	{
		return $this->quantity_done_discrepancy;
	}
}