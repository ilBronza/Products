<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class VehicleRowsByContainerFieldsGroupParametersFile extends FieldsGroupParametersFile
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
							'priority' => 10,
						],
						'width' => '2em',
					],
					'mySelfHistory' => [
						'translatedName' => 'Storico',
						'type' => 'links.fetcher',
						'faIcon' => 'clock-rotate-left',
						'fetcher' => [
							'urlMethod' => 'getHistoryUrlPlaceholder',
							'type' => 'click',
						],
						'visible' => false,
						'width' => '2em',
					],
					'sellable' => [
						'type' => 'relations.belongsTo',
						'translatedName' => 'Tipologia mezzo',
					],
					'sellableSupplier.supplier.target' => [
						'type' => 'links.seeName',
						'icon' => false,
						'width' => '180px',
					],
					'mySelfChangeSellableSupplier' => 'products::sellableSuppliers.changeSellableSupplier',
					'cost_gross_day' => [
						'type' => 'editor.price',
						'saveButton' => true,
						'refreshRow' => true,
					],
					'description' => [
						'type' => 'editor.text',
					],
					'confirmed' => [
						'translatedName' => 'Conf',
						'refreshRow' => true,
						'type' => 'editor.toggle',
					],
					'mySelfDelete' => 'links.delete'
				],
		];
	}
}
