<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

abstract class SellableSupplierBaseEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
	abstract static function getTypedFieldsets() : array;

	static function _getFieldsetsParameters() : array
	{
		$starting = static::getStartingFieldsets();
		$typed = static::getTypedFieldsets();
//		$ending = static::getEndingFieldsets();

//		return $starting + $typed + $ending;
		return $starting + $typed;
	}
	
	static function getStartingFieldsets() : array
	{
		return [
			'package' => [
				'translationPrefix' => 'products::fields',
				'fields' => [

					'sellable_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|required',
						'relation' => 'sellable',
						'readOnly' => true,
					]
				],
				'width' => ['1-3@l', '1-2@m']
			]
		];
	}

//	static function getEndingFieldsets() : array
//	{
//		return [
//			'other' => [
//				'translationPrefix' => 'products::fields',
//				'fields' => [
//				],
//				'width' => ['1-3@l', '1-2@m']
//			]
//		];
//	}
}
