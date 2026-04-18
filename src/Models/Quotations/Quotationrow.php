<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\CRUD\Interfaces\CrudReorderableModelInterface;
use IlBronza\Payments\Models\Interfaces\InvoiceDetailInterface;
use IlBronza\Products\Models\Interfaces\RowInterface;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\Traits\Order\CommonOrderrowQuotationrowTrait;
use IlBronza\Products\Models\Traits\Orderrow\CommonOrderrowQuotationrowCheckersTrait;
use IlBronza\Products\Models\Traits\Orderrow\CommonOrderrowQuotationrowGettersTrait;
use IlBronza\Products\Models\Traits\Orderrow\CommonOrderrowQuotationrowPricesTrait;
use IlBronza\Timings\Interfaces\TimelineInterface;

class Quotationrow extends ProductPackageBaseRowModel implements CrudReorderableModelInterface, InvoiceDetailInterface, TimelineInterface, RowInterface
{
	static $modelConfigPrefix = 'quotationrow';

	use CommonOrderrowQuotationrowTrait;
	use CommonOrderrowQuotationrowCheckersTrait;
	use CommonOrderrowQuotationrowPricesTrait;
	use CommonOrderrowQuotationrowGettersTrait;

	static $deletingRelationships = [];
	protected $casts = [
		'starts_at' => 'date',
		'ends_at' => 'date',
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

	public function container()
	{
		return $this->quotation();
	}

	public function getModelContainerRelationName() : string
	{
		return 'quotation';
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::gpc());
	}

	public function orderrow()
	{
		return $this->hasOne(Orderrow::gpc());
	}

	public function getTimelineHtmlClassesString() : ? string
	{
		return null;
	}

	public function getCompletionPercentage() : float
	{
		return 0;
	}
}