<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SellableSupplierEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $fields = [];

        foreach($this->getModel()->getCachedPriceFieldsForSellable() as $field)
            $fields[$field] = ['number' => 'numeric|nullable|min:0'];

        return [
            'prices' => [
                'translationPrefix' => 'products::fields',
                'fields' => $fields,
                'width' => ["large"]
            ]
        ];
    }
}
