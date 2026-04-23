<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SellableSupplierEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $fields = [];

        $target = $this->getModel()->getSellable()->getTarget();

        $configPrefix = $target->getPackageConfigPrefix();

        foreach($this->getModel()->getCachedPriceFieldsForSellable() as $field => $measurementUnit)
            $fields[$field] = ['number' => 'numeric|nullable|min:0'];

        return [
            'prices' => [
                'translationPrefix' => $configPrefix . '::fields',
                'fields' => $fields,
                'width' => ["large"]
            ]
        ];
    }
}
