<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

abstract class SellableSupplierBaseFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	abstract static function getTypedFields() : array;

	static function getFieldsGroup() : array
	{
		$starting = static::getStartingFields();
		$central = static::getTypedFields();
		$ending = static::getEndingFields();

		return [
			'translationPrefix' => 'products::fields',
			'fields' => $starting + $central + $ending
		];
	}

	static function getStartingFields() : array
	{
		return [
			'mySelfPrimary' => 'primary',
			'supplier.target' => [
				'type' => 'links.seeName',
				'width' => '195px'
			],
			//			'mySelfJson' => 'json',
		];
	}

	static function getEndingFields() : array
	{
		return [
			'mySelfAssign' => 'products::quotationrows.assignSellableSupplier',
			'mySelfDelete' => 'links.delete'
		];
	}
}