<?php

namespace IlBronza\Products\Models\Orderrows\Catering;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Products\Models\Orderrows\Catering\CateringProductRowCommonTrait;
use IlBronza\Products\Models\Sellables\ProductOrderrow as IbProductOrderrow;

class ProductOrderrow extends IbProductOrderrow
{
	use CateringProductRowCommonTrait;

	public string $fieldsGroupParametersKey = 'cateringProductOrderrow';

	protected $casts = [
		// 'phase' => ExtraField::class,
		'people_coefficient' => ExtraField::class,
		'served_at_table' => ExtraField::class,
	];

	public function hasPhase(string $phase) : bool
	{
		return $this->phase == $phase;
	}

	public function hasEmptyPhase() : bool
	{
		return ! $this->phase;
	}

	public function getPdfDescriptionString()
	{
		return ucfirst($this->getName());
	}

	public function getPdfDescriptionCost()
	{
		return number_format($this->getTotalClientPrice(), 2, ',', '&#729;');
	}
}