<?php

namespace IlBronza\Products\Http\Parameters\Datatables\Catering;

use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\CateringProductOrderrowsFieldsGroupParametersFile as BaseCateringProductOrderrowsFieldsGroupParametersFile;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

class CateringProductOrderrowsFieldsGroupParametersFile extends BaseCateringProductOrderrowsFieldsGroupParametersFile
{
	static function getFieldsGroup(ProductPackageBaseRowcontainerModel $parentModel) : array
	{
		$result = parent::getFieldsGroup($parentModel);

		unset($result['fields']['sellableSupplier.supplier.target']);
		unset($result['fields']['sellable.target_id']);

		return $result;
	}
}
