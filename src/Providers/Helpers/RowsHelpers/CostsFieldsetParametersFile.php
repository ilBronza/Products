<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceFormFieldsHelper;
use Illuminate\Database\Eloquent\Model;

class CostsFieldsetParametersFile extends FieldsetParametersFile
{
	static function getCostsFieldsetByModel(Model $model)
	{
		if (! $model instanceof SellableItemInterface)
			return [];

		$fields = [];

		foreach(SellablePriceFormFieldsHelper::getFieldsByModel(
				$model
			) as $field => $parameters)

		$fields[$field] = $parameters;

		return $fields;
	}
}