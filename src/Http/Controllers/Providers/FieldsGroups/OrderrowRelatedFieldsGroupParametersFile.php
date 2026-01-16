<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OrderrowRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'order.client' => 'clients::client.client',
                'order.project' => 'products::projects.project',
                'order' => 'products::orders.order',
                'order.event' => 'app::models.orders.event',
	            'sellable.name' => 'flat',
                'clientOperator.employment_label_text' => [
                    'translatedName' => 'Rapporto',
                    'type' => 'flat',
                    'width' => '5em'
                ],

                'starts_at' => [
                    'type' => 'dates.date',
                    'order' => [
                        'priority' => 1236,
                        'type' => 'DESC'
                    ]
                ],
                'ends_at' => 'dates.date',
                'cost_gross_day' => 'numbers.number2',
                'calculated_cost_company' => 'numbers.number2',
                'calculated_cost_company_total' => 'numbers.number2'

            ]
        ];
	}
}