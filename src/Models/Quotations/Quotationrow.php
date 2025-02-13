<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\CRUD\Interfaces\CrudReorderableModelInterface;
use IlBronza\Payments\Models\Interfaces\InvoiceDetailInterface;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\Traits\Order\CommonOrderrowQuotationrowTrait;

class Quotationrow extends ProductPackageBaseRowModel implements CrudReorderableModelInterface, InvoiceDetailInterface
{
	static $modelConfigPrefix = 'quotationrow';

	use CommonOrderrowQuotationrowTrait;

	protected $casts = [
		'starts_at' => 'date',
		'ends_at' => 'date'
	];

	static $deletingRelationships = [];
	protected $with = ['sellable'];

	public function getModelContainer()
	{
		return $this->getQuotation();
	}

	public function getQuotation() : ? Quotation
	{
		return $this->quotation;
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::getProjectClassName());
	}

	public function orderrow()
	{
		return $this->hasOne(Orderrow::gpc());
	}

}