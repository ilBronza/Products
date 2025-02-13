<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class QuotationRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
{
    static function getFieldsGroup() : array
    {
        return [
            'translationPrefix' => 'products::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfSee' => [
					'type' => 'links.seeName',
	                'width' => '8em'
                ],
                'date' => 'dates.date',
                'quotationrows_count' => 'flat',
	            'client' => [
		            'type' => 'relations.belongsTo',
		            'width' => '165px'
	            ],
	            'project' => [
		            'type' => 'relations.belongsTo',
		            'width' => '165px'
	            ],
                'directPrice.price' => 'flat',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}