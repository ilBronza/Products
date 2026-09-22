<?php

namespace IlBronza\Products\Providers\DatatablesFields\Catering;

use IlBronza\Datatables\DatatablesFields\DatatableFieldFlat;
use Illuminate\Support\Collection;

class DatatableFieldAllergens extends DatatableFieldFlat
{
	public ?string $forcedStandardName = 'allergens';

	public function transformValue($value)
	{
		if (! $value instanceof Collection)
			return null;

		return $value
			->map(fn ($allergen) => $allergen->renderText())
			->filter()
			->implode('<br />');
	}
}
