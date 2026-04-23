<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceFormFieldsHelper;
use Illuminate\Database\Eloquent\Model;

class RowFieldsetParametersFile extends FieldsetParametersFile
{
	static function addRowCostsFieldset(array $fieldsets, $model) : array
	{
		$fields = static::getCalculatedCostsFieldsetByModel(
			$model
		);

		$fields['calculated_single_cost'] = ['number' => 'numeric|nullable'];
		$fields['calculated_single_revenue'] = ['number' => 'numeric|nullable'];

		$fields['calculated_total_row_cost'] = ['number' => 'numeric|nullable'];
		$fields['approved_total_row_cost'] = ['boolean' => 'bool|nullable'];

		$fields['calculated_total_row_revenue'] = ['number' => 'numeric|nullable'];
		$fields['approved_total_row_revenue'] = ['boolean' => 'bool|nullable'];

		$fieldsets['costs'] = [
			'translationPrefix' => 'products::fields',
			'fields' => $fields,
			'width' => ['large']
		];

		return $fieldsets;
	}

	static function getCalculatedCostsFieldsetByModel(Model $model)
	{
		if (! $model instanceof SellableItemInterface)
			return [];

		$fields = [];

		foreach(SellablePriceFormFieldsHelper::getFieldsByModel(
				$model
			) as $field => $parameters)

		$fields['calculated_' . $field] = $parameters;

		return $fields;
	}

	// static function addSummaryCostsFields(array $fieldsgroups) : array
	// {
	// 	$fields = $fieldsgroups['fields'];

	// 	foreach([
	// 		'calculated_total_row_cost' => 'editor.price',
	// 		'approved_total_row_cost' => 'editor.toggle',

	// 		'calculated_total_row_revenue' => 'editor.price',
	// 		'approved_total_row_revenue' => 'editor.toggle'
	// 	] as $field => $parameters)
	// 		$fields[$field] = $parameters;

	// 	$fieldsgroups['fields'] = $fields;

	// 	return $fieldsgroups;
	// }
}