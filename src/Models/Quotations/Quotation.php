<?php

namespace IlBronza\Products\Models\Quotations;

use Carbon\Carbon;
use IlBronza\Buttons\Button;
use IlBronza\Products\Models\Order;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Traits\Order\CommonOrderQuotationTrait;
use IlBronza\Products\Models\Traits\Quotation\QuotationRelationshipsTrait;

class Quotation extends ProductPackageBaseRowcontainerModel
{
	use CommonOrderQuotationTrait;
	use QuotationRelationshipsTrait;

	static $modelConfigPrefix = 'quotation';
	protected $deletingRelationships = ['quotationrows'];

	protected $casts = [
		'date' => 'date'
	];

	public function getStoreQuotationrowUrl() : string
	{
		return $this->getKeyedRoute('storeQuotationrow', [
			'quotation' => $this->getKey(),
		]);
	}

	public function getAddQuotationrowByTypeUrl(string $type, bool $table = false) : string
	{
		return $this->getKeyedRoute('addQuotationrow', [
			'quotation' => $this->getKey(),
			'type' => $type,
			'table' => $table
		]);
	}

	public function getConvertToOrderUrl() : string
	{
		return $this->getKeyedRoute('convertToOrder', [
			'quotation' => $this->getKey()
		]);
	}

	public function getPossibleSellablesByType(string $type) : array
	{
		$types = $this->getQuotationrowsPossibleSellableTypes();

		$type = strtolower($type);

		return $types[$type]();
	}

	public function getDescription()
	{
		return 'aggiungere la descrizione alle quotazioni e convertire in commessa';
	}

	public function getDate() : ? Carbon
	{
		return $this->date;
	}

	public function order()
	{
		return $this->hasOne(Order::gpc());
	}

	public function getOrderCode() : string
	{
		return $this->getOrder()->getCode();
	}

	public function getOrder() : ?Order
	{
		return $this->order;
	}

	public function hasOrder() : bool
	{
		return !! $this->getOrder();
	}

	public function convertToOrder()
	{
		$helperClass = $this->getConfigByKey('quotationToOrderConverterHelper');

		$helper = new $helperClass($this);

		return $helper->convertQuotationToOrder();
	}

	public function getConvertToOrderButton() : ? Button
	{
		if($this->hasOrder())
			return null;

		return Button::create([
			'href' => $this->getConvertToOrderUrl(),
			'text' => 'products::quotations.convertToOrder',
			'icon' => 'file-pdf'
		]);

	}
}