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
		$result = [];

		foreach (static::getPricesByModel($model) as $key => $value)
		{
			$fieldName = is_string($key) ? $key : $value;

			if (! is_string($fieldName))
				continue;

			$result[$fieldName] = $baseParameters;
		}

		return $result;
	}

	static function getCalculatedFieldsByModel(mixed $model, array $baseParameters = ['type' => 'editor.price', 'refreshRow' => true])
	{
		$result = [];

		foreach (static::getPricesByModel($model) as $key => $value)
		{
			$fieldName = is_string($key) ? $key : $value;

			if (! is_string($fieldName))
				continue;

			$result['calculated_' . $fieldName] = $baseParameters;
		}

		return $result;
	}

	static function getPricesByModel(mixed $model)
	{
		if ($model instanceof Sellable)
			return $model->getPriceFieldsForSellable();
		
		if (is_object($model) && method_exists($model, 'getPriceFieldsForSellable'))
			return $model->getPriceFieldsForSellable();

		return [];
	}
}

