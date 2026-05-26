<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SellableSupplierEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $fields = [];

        $sellable = $this->getModel()->getSellable();

        $prices = $this->getModel()->getCachedPriceFieldsForSellable();

        $casts = [];

        foreach ($prices as $field => $measurementUnit) {
            $casts[$field] = CastFieldPrice::class . ":{$field},$measurementUnit";
        }

        $this->getModel()->mergeCasts($casts);

        foreach($prices as $field => $measurementUnit)
            $fields[$field] = ['number' => 'numeric|nullable|min:0'];

        $configPrefix = $sellable->getTarget()?->getPackageConfigPrefix();

        return [
            'prices' => [
                'translationPrefix' => ($configPrefix) ? $configPrefix . '::fields' : 'fields',
                'fields' => $fields,
                'width' => ["large"]
            ]
        ];
    }
}
