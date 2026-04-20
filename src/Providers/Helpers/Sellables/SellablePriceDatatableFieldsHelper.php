<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\Products\Models\Sellables\Sellable;

class SellablePriceDatatableFieldsHelper
{
	/**
	 * Ritorna i fields per Datatables `FieldsGroupParametersFile`.
	 *
	 * Esempio output:
	 * [
	 *   'cost_per_day' => ['type' => 'editor.price', 'refreshRow' => true],
	 *   'cost_per_hour' => ['type' => 'editor.price', 'refreshRow' => true],
	 * ]
	 */
	static public function getFieldsByModel(mixed $model, array $baseParameters = ['type' => 'editor.price', 'refreshRow' => true]) : array
	{
		if ($model instanceof Sellable)
			$prices = $model->getPriceFieldsForSellable();
		elseif (is_object($model) && method_exists($model, 'getPriceFieldsForSellable'))
			$prices = $model->getPriceFieldsForSellable();
		else
			$prices = [];

		$result = [];

		foreach ($prices as $key => $value)
		{
			$fieldName = is_string($key) ? $key : $value;

			if (! is_string($fieldName))
				continue;

			$result[$fieldName] = $baseParameters;
		}

		return $result;
	}
}

