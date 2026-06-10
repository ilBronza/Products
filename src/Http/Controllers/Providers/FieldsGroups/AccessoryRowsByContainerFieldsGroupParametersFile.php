<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Products\Models\AccessoryType;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFieldsGroupParametersFile;

class AccessoryRowsByContainerFieldsGroupParametersFile extends RowsFieldsGroupParametersFile
{
	static function getFieldsGroup(ProductPackageBaseRowcontainerModel $parentModel) : array
	{
		$helper = static::createByContainer($parentModel);

        $fields = $helper->getRowStartingFields();

		unset($fields['starts_at']);
		unset($fields['ends_at']);

		$fields = static::addCostsFields(
			$fields,
			AccessoryType::gpc()::make()
		);

		$fields = static::addPdfFields(
			$fields,
		);

		return static::finalizeCostsFieldsGroup([
			'translationPrefix' => 'products::fields',
			'fields' => $fields
		], AccessoryType::gpc()::make());
	}
}