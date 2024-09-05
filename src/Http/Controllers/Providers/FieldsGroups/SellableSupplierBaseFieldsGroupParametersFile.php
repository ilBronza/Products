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
			'mySelfEdit' => 'links.edit',
			'mySelfSee' => 'links.see',
			'name' => 'flat',
			'supplier.target' => 'links.seeName',
			'supplier.target.address.city' => 'flat',
			'supplier.target.address.province' => 'flat',
//			'mySelf' => 'json',
//			'mySelfClass' => 'models.classname',
		];
	}

	static function getEndingFields() : array
	{
		return [
			'mySelfDelete' => 'links.delete'
		];
	}
}