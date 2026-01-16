<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ProductRowsByContainerFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
            'fields' =>
	            [
				'mySelfPrimary' => 'primary',
				'sorting_index' => [
					'type' => 'flat',
					'order' => [
						'priority' => 10
					],
					'width' => '2em'
				],
				'mySelfHistory' => [
					'translatedName' => 'Storico',
					'type' => 'links.fetcher',
					'faIcon' => 'clock-rotate-left',
					'fetcher' => [
						'urlMethod' => 'getHistoryUrlPlaceholder',
						'type' => 'click',
						//					'mode' => 'iframe'
					],
					'visible' => false,
					'width' => '2em'
				],
				'sellable' => 'products::sellables.sellable',
				'sellableSupplier.supplier.target' => [
					'type' => 'links.seeName',
					'icon' => false,
					'width' => '180px',
				],
				'mySelfChangeSellableSupplier' => [
					'type' => 'products::sellableSuppliers.changeSellableSupplier',
					'width' => '6em',
					'defaultWidth' => '3em',
				],
				'mySelfMobile.sellableSupplier.supplier.target' => [
					'type' => 'function',
					'function' => 'getMobileString',
					'width' => '6em'
				],

				'cost_gross_day' => [
					'type' => 'editor.price',
					'saveButton' => true,
					'refreshRow' => true,
					//				'reloadTable' => true,
				],

				'description' => [
					'type' => 'editor.text'
				],

				'confirmed' => [
					'translatedName' => 'Conf',
					'refreshRow' => true,
					//				'reloadTable' => true,
					'type' => 'editor.toggle',
				],

            ]
        ];

	}
}