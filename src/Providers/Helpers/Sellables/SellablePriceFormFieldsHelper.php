<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\Products\Models\Sellables\Sellable;

class SellablePriceFormFieldsHelper
{
	/**
	 * Ritorna l'array `fields` per i fieldset a partire dal model passato.
	 *
	 * Accetta sia target sellable (che espone `getPriceFieldsForSellable()`) sia un `Sellable`.
	 */
	static public function getFieldsByModel(mixed $model, array $fieldParameters = ['number' => 'numeric|nullable']) : array
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

			$result[$fieldName] = $fieldParameters;
		}

		return $result;
	}
}

