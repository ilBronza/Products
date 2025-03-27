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

	static $deletingRelationships = [];
	protected $casts = [
		'starts_at' => 'date',
		'ends_at' => 'date'
	];

	protected $with = ['sellable'];

	public function getModelContainer()
	{
		return $this->getQuotation();
	}

	public function getQuotation() : ?Quotation
	{
		return $this->quotation;
	}

	public function modelContainer()
	{
		return $this->quotation();
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::gpc());
	}

	public function orderrow()
	{
		return $this->hasOne(Orderrow::gpc());
	}

}