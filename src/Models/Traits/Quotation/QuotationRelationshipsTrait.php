<?php

namespace IlBronza\Products\Models\Traits\Quotation;

use IlBronza\Products\Models\Quotations\Quotationrow;
use Illuminate\Support\Collection;

trait QuotationRelationshipsTrait
{
	public function quotationrows()
	{
		return $this->hasMany(Quotationrow::gpc());
	}

	public function getQuotationrows() : Collection
	{
		return $this->quotationrows;
	}

	public function rows()
	{
		return $this->quotationrows();
	}
}