<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\Permissions\EconomicsPermissionsHelper;

class SellablePriceFormFieldsHelper
{
	static function getDefaultFieldsParameters() : array
	{
		return ['number' => 'numeric|nullable'];
	}

	static function normalizeFieldParameters(array $fieldParameters) : array
	{
		if (isset($fieldParameters['type']))
			return $fieldParameters;

		if (count($fieldParameters) !== 1)
			return $fieldParameters;

		return FieldsetParametersFile::getFieldParametersFromString($fieldParameters);
	}

	static function addFieldsParametersRoles(array $fieldParameters) : array
	{
		$fieldParameters = static::normalizeFieldParameters($fieldParameters);

		if (! $role = EconomicsPermissionsHelper::roleName())
			return $fieldParameters;

		$roles = $fieldParameters['roles'] ?? [];

		if (! is_array($roles))
			$roles = [$roles];

		if (! in_array($role, $roles, true))
			$roles[] = $role;

		$fieldParameters['roles'] = $roles;

		return $fieldParameters;
	}

	/**
	 * Ritorna l'array `fields` per i fieldset a partire dal model passato.
	 *
	 * Accetta sia target sellable (che espone `getPriceFieldsForSellable()`) sia un `Sellable`.
	 */
	static public function getFieldsByModel(mixed $model, array $fieldParameters = null) : array
	{
		if(! $fieldParameters)
			$fieldParameters = static::getDefaultFieldsParameters();

		$fieldParameters = static::addFieldsParametersRoles($fieldParameters);

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

