<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OperatorRowsByContainerFieldsGroupParametersFile extends FieldsGroupParametersFile
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
				'sellable' => [
					'type' => 'relations.belongsTo',
					'translatedName' => 'Mansione',
//					'mainHeader' => [
//						'label' => 'Operatore',
//						'colspan' => 5
//					],
					'width' => '10em',
				],
				'sellableSupplier.supplier.target' => [
					'type' => 'links.seeName',
					'translatedName' => 'Operatore',
					'icon' => false,
					'width' => '180px',
				],
				'mySelfChangeSellableSupplier' => [
					'type' => 'products::sellableSuppliers.changeSellableSupplier',
					'width' => '6em',
					'defaultWidth' => '3em',
				],
				'mySelfAlerts' => [
					'type' => 'notifications.alerts',
					'function' => 'getOperatorAlerts',
					'width' => '3em',
				],
//				'is_enpals_vat' => [
//					'type' => 'boolean',
//					'showOnlyTrue' => true,
//					'trueIcon' => 'skull-crossbones'
//				],
				//			'sellableSupplier.supplier.target.valid_employment_type_string' => [
				//			'clientOperator.employment.label_text' => [
				//				'type' => 'flat',
				//				'width' => '80px'
				//			],

				'clientOperator' => [
					'translatedName' => 'Rapporto',
					'type' => 'links.fetcher',
					'textParameter' => 'employment_label_text',
					'fetcher' => [
						'urlMethod' => 'getClientOperatorPopupUrl',
						'type' => 'click',
						'mode' => 'lightbox',
						'target' => 'row'
					],
					'width' => '5em'
				],

				'mySelfMobile.sellableSupplier.supplier.target' => [
					'type' => 'function',
					'function' => 'getMobileString',
					'width' => '6em'
				],
//				'has_hotel_room' => [
//					'type' => 'boolean',
//					'trueIcon' => 'bed'
//				],
//				'has_vehicle_seat' => [
//					'type' => 'boolean',
//					'trueIcon' => 'car'
//				],
//				'half_start' => [
//					'type' => 'editor.toggle',
//					'nullable' => true,
//					'refreshRow' => true,
//					//				'reloadTable' => true,
//					'mainHeader' => [
//						'label' => 'Giornate',
//						'colspan' => 7
//					],
//				],
				'starts_at' => [
					'type' => 'editor.dates.date',
					'refreshRow' => true,
					//				'reloadTable' => true,
				],
				'mySelfdifferent_starts_at' => [
					'type' => 'function',
					'function' => 'getHasDifferentStartsAt',
					'valueAsRowClass' => true,
					'visible' => false,
				],
				'ends_at' => [
					'type' => 'editor.dates.date',
					'refreshRow' => true,
					//				'reloadTable' => true,
				],
				'mySelfdifferent_ends_at' => [
					'type' => 'function',
					'function' => 'getHasDifferentEndsAt',
					'valueAsRowClass' => true,
					'visible' => false,
				],
//				'half_end' => [
//					'type' => 'editor.toggle',
//					'nullable' => true,
//					'refreshRow' => true,
//					//				'reloadTable' => true
//				],
				'quantity_on_total' => [
					'translatedName' => 'Tot',
					'type' => 'flat',
					'width' => '45px'
				],
				'cost_gross_day' => [
					'type' => 'editor.price',
					'translatedName' => 'Lordo',
					// 'saveButton' => true,
					'refreshRow' => true,
					//				'reloadTable' => true,
//					'mainHeader' => [
//						'label' => 'Costi singoli',
//						'colspan' => 2
//					],
				],
				// 'control_cost_gross_day' => [
				// 	'type' => 'flat',
				// 	'translatedName' => 'Controllo lordo',
				// ],

				//			'calculated_daily_allowance_string' => [
				//				'type' => 'flat',
				//				'headerData' => [
				//					'reloadtable' => true,
				//				],
				//				'width' => '35px'
				//			],

				'calculated_cost_company' => [
					'translatedName' => 'Costo az.',
					'type' => 'numbers.number2',
					'width' => '75px'
				],

				//			'cost_company_approver' => [
				//				'type' => 'editor.toggle',
				//			'refreshRow' => true,
				//			//				'reloadTable' => true,
				//			],

				'calculated_cost_gross_operator_total' => [
					'translatedName' => 'Lordo',
//					'mainHeader' => [
//						'label' => 'Costi totali',
//						'colspan' => 6
//					],
					'type' => 'numbers.number2',
				],
				'client_price_approver' => 'boolean',
				'cost_company_approver' => 'editor.toggle',
				'calculated_daily_allowance_total' => [
					'type' => 'numbers.number2',
					'translatedName' => 'Diaria',
				],
				'gross_and_allowance_total' => [
					'type' => 'numbers.number2',
					'translatedName' => 'Lordo + Diaria',
				],
				'operator_company_cost_per_days' => [
					'type' => 'numbers.number2',
					'translatedName' => 'Costo az',
				],
				//			'status' => [
				//				'type' => 'editor.select',
				//				'possibleValuesArray' => [
				//					'inviare' => 'Da inviare',
				//					'inviato' => 'Inviato',
				//					'ricevuto' => 'Ricevuto',
				//				],
				//				'width' => '75px',
				//			'refreshRow' => true,
				//			//				'reloadTable' => true,
				//				'nullable' => true,
				//			],
				//			'approve_daily_allowances_costs' => [
				//				'type' => 'editor.toggle',
				//				'mainHeader' => [
				//					'label' => 'Costi',
				//					'colspan' => 5
				//				],
				//			'refreshRow' => true,
				//			//				'reloadTable' => true,
				//			],
				'description' => [
					'type' => 'editor.text'
				],

				'calculated_row_total' => [
					'type' => 'numbers.number2',
//					'mainHeader' => [
//						'label' => 'Costi',
//						'colspan' => 4
//					],
				],
				'calculated_cost_company_total' => [
					'type' => 'editor.price',
					'reloadTable' => true
				],
				// 'mySelfPriceCalculatedCostCompanyHtmlClass' => [
				// 	'type' => 'function',
				// 	'function' => 'getCalculatedCostCompanyHtmlClass',
				// 	'valueAsRowClass' => true,
				// 	'visible' => false,
				// ],

//				'mySelfPriceCalculatedCostCompanyTotalHtmlClass' => [
//					'type' => 'function',
//					'function' => 'getCalculatedCostCompanyTotalHtmlClass',
//					'valueAsRowClass' => true,
//					'visible' => false,
//				],
				//			'calculated_row_total' => [
				//				'type' => 'numbers.number2',
				//			],
				// 'mySelfPriceCalculatedCostGrossOperatorTotalHtmlClass' => [
				// 	'type' => 'function',
				// 	'function' => 'getCalculatedCostGrossOperatorTotalHtmlClass',
				// 	'valueAsRowClass' => true,
				// 	'visible' => false,
				// ],
				// 'mySelfPriceCalculatedCostGrossDayHtmlClass' => [
				// 	'type' => 'function',
				// 	'function' => 'getCalculatedCostGrossDayHtmlClass',
				// 	'valueAsRowClass' => true,
				// 	'visible' => false,
				// ],
				//			'approved' => [
				//				'type' => 'editor.toggle',
				//				'nullable' => true,
				//			'refreshRow' => true,
				//			//				'reloadTable' => true,
				//				//				'mainHeader' => [
				//				//					'label' => 'Conf',
				//				//					'colspan' => 1
				//				//				],
				//				'width' => '25px',
				//			],
				'confirmed' => [
					'translatedName' => 'Conf',
					'refreshRow' => true,
					//				'reloadTable' => true,
					'type' => 'editor.toggle',
				],
				'convocated_when' => [
					'type' => 'editor.text',
					'translatedName' => 'Ora',
//					'mainHeader' => [
//						'label' => 'Convocazione',
//						'colspan' => 2
//					],
					'width' => '3em'
				],
				'convocated_where' => 'editor.text',
				'mySelfSave' => 'editor.save',
            ]
        ];

	}
}