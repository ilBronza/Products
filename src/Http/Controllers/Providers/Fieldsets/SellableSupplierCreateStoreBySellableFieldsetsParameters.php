<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierCreateStoreFieldsetsParameters;

class SellableSupplierCreateStoreBySellableFieldsetsParameters extends SellableSupplierCreateStoreFieldsetsParameters
{
    public function _getFieldsetsParameters() : array
    {
        $result = parent::_getFieldsetsParameters();

        $result['package']['fields']['sellable_id']['type'] = 'text';
        $result['package']['fields']['sellable_id']['visible'] = false;

        return $result;
    }
}
