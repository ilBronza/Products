<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

abstract class SellableSupplierBaseFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroupByContainerModel(string $containerModel) : array
	{
		$starting = static::getStartingFields($containerModel);
		$central = static::getTypedFields($containerModel);
		$ending = static::getEndingFields($containerModel);

		return [
			'translationPrefix' => 'products::fields',
			'fields' => $starting + $central + $ending
		];
	}

	static function getStartingFields(string $containerModel) : array
	{
		return [
			'mySelfPrimary' => 'primary',
			'supplier.target' => [
				'type' => 'links.seeName',
				'width' => '195px'
			],
			'mySelfAssign' => "products::{$containerModel}rows.assignSellableSupplier",
			//			'mySelfJson' => 'json',
		];
	}

	abstract static function getTypedFields() : array;

	static function getEndingFields() : array
	{
		return [
			'mySelfDelete' => 'links.delete'
		];
	}

	static function getFieldsGroup() : array
	{
		dd('problema, usare quello tipizzato');
		$starting = static::getStartingFields();
		$central = static::getTypedFields();
		$ending = static::getEndingFields();

		return [
			'translationPrefix' => 'products::fields',
			'fields' => $starting + $central + $ending
		];
	}
}