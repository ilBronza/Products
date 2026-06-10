<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Providers\Helpers\Permissions\EconomicsPermissionsHelper;

class QuotationrowFieldsGroupParametersFile extends FieldsGroupParametersFile
{
    static function getFieldsGroup() : array
    {
        return EconomicsPermissionsHelper::mergeInto([
            'translationPrefix' => 'products::fields',
            'fields' =>
            [
                'mySelfPrimary' => 'primary',
                'sellable' => [
                    'type' => 'links.seeName',
                    'width' => '20rem'
                ],
                'quotation' => 'products::orders.order',
                'quotation.client' => 'clients::client.client',
                'quotation.project' => 'products::projects.project',
                'quantity' => 'flat',
                'cost_company' => 'flat',
                'client_price' => 'flat',
            ]
        ], [
            'cost_company',
            'client_price',
        ]);
    }
}