<?php

namespace IlBronza\Products\Providers\Helpers\Quotations;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Ukn\Ukn;

use function collect;

class QuotationToOrderConverterHelper
{
	public Quotation $quotation;
	public Order $order;

	public function __construct(Quotation $quotation)
	{
		$this->quotation = $quotation;

		if($order = $this->quotation->getOrder())
		{
			Ukn::e(trans('products:quotations.thisQuotationHasAlreadyBeenConvertedToOrder'));

			return $order;
		}
	}

	static function _bindQuotationrowToOrderrow(Quotationrow $quotationrow, Orderrow $orderrow) : Orderrow
	{
		dd($orderrow);
	}

	public function getQuotation() : Quotation
	{
		return $this->quotation;
	}

	public function getOrder() : Order
	{
		return $this->order;
	}

	public function getOrderName() : string
	{
		return $this->getQuotation()->getName();
	}

	public function setOrder()
	{
		$this->order = Order::gpc()::make();
		$this->order->name = $this->getOrderName();

		$this->getQuotation()->order()->save($this->order);

		$this->order = static::_bindQuotationToOrder($this->getQuotation(), $this->order);
	}

	static function _convertQuotationrowToOrderrow(Order $order, Quotationrow $quotationrow) : Orderrow
	{
		$orderrow = Orderrow::gpc()::make();

		$orderrow->order()->associate($order);

		$quotationrow->orderrow()->save($orderrow);

		return static::_bindQuotationrowToOrderrow($quotationrow, $orderrow);
	}

	public function convertQuotationrowToOrderrow(Quotationrow $quotationrow) : Orderrow
	{
		return static::_convertQuotationrowToOrderrow($this->getOrder(), $quotationrow);
	}

	public function setOrderrows() : void
	{
		$quotationrows = $this->getQuotation()->getQuotationRows();

		$orderrows = collect();

		foreach($quotationrows as $quotationrow)
			$orderrows->push(
				$this->convertQuotationrowToOrderrow($quotationrow)
			);

		$this->getOrder()->setRelation('orderrows', $orderrows);
	}

	public function convertQuotationToOrder() : Order
	{
		$this->setOrder();
		$this->setOrderrows();

		return $this->getOrder();
	}
}