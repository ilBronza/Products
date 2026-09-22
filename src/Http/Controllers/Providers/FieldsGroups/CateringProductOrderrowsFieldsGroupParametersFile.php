<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFieldsGroupParametersFile;
use Illuminate\Database\Eloquent\Model;

class CateringProductOrderrowsFieldsGroupParametersFile extends RowsFieldsGroupParametersFile
{
	static function getFieldsGroup(ProductPackageBaseRowcontainerModel $parentModel) : array
	{
		$helper = static::createByContainer($parentModel);

		$fields = $helper->getRowStartingFields();

		unset($fields['starts_at']);
		unset($fields['ends_at']);

		$fields['calculated_quantity_coefficient'] = [
					'type' => 'editor.numeric',
					'refreshRow' => true,
					'fieldsGroupsDefinitions' => [
						'managementParameters'
					],
				];

		$fields['people_coefficient'] = [
					'type' => 'editor.select',
					'possibleValuesArray' => $parentModel->getPossiblePeopleCoefficientArrayValues(),
					'refreshRow' => true,
				];

		$fields['phase'] = [
					'type' => 'editor.select',
					'possibleValuesArray' => $parentModel->getPossiblePhasesArrayValues(),
					'refreshRow' => true
				];

		$fields['different_quantity_coefficient'] = 'flatRowClass';

		$fields['served_at_table'] = [
					'refreshRow' => true,
					'tooltip' => 'areo',
					'type' => 'editor.toggle',
				];

		$fields['allergens_list'] = 'products::catering.allergens';

		$fields = static::addCostsFields(
			$fields,
			Product::gpc()::make()
		);

		$fields = static::addPdfFields(
			$fields,
		);

		return static::finalizeCostsFieldsGroup([
			'translationPrefix' => 'products::fields',
			'fields' => $fields,
			'summary' => $helper->getStandardCostsSummaryFields(),
		], Product::gpc()::make());
	}
}
