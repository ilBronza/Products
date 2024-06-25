<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class QuotationrowRelatedFieldsGroupParametersFile extends QuotationrowFieldsGroupParametersFile
{
    static function getFieldsGroup() : array
    {
        return [
            'translationPrefix' => 'products::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'sellableSupplier.sellable' => 'links.seeName',
                'quotation' => 'relations.belongsTo',
                'quotation.project' => 'links.seeName',
                'quotation.client' => 'links.seeName',
                'starts_at' => 'dates.date',
                'ends_at' => 'dates.date',
                'quantity' => 'flat',
                'sellableSupplier.directPrice' => '_fn_getName',
                'directPrice' => '_fn_getName',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}