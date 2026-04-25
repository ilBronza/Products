<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceDatatableFieldsHelper;
use Illuminate\Database\Eloquent\Model;

class CostsFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function addCostsFieldsByModel(array $fields, Model $model) : array
	{
		if (! $model instanceof SellableItemInterface)
			return $fields;

		$fields = array_merge(
			$fields, 
			SellablePriceDatatableFieldsHelper::getCalculatedFieldsByModel(
				$model
			)
		);

		return $fields;
	}

	static function addStandardCostsFieldsByModel(array $fields, Model $model)
	{
		if (! $model instanceof SellableItemInterface)
			return $fields;

		$fields = array_merge(
			$fields, 
			SellablePriceDatatableFieldsHelper::getStandardFieldsByModel(
				$model
			)
		);

		return $fields;		
	}

	static function addStandardCostsFieldsByModelPlusDelete(array $fields, Model $model) : array
	{
        $fields = static::addStandardCostsFieldsByModel(
            $fields,
            $model
        );

		$fields['mySelfDelete'] = 'links.delete';

		return $fields;
	}
}