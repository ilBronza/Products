<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierCreateStoreFieldsetsParameters;

class SellableSupplierCreateStoreBySupplierFieldsetsParameters extends SellableSupplierCreateStoreFieldsetsParameters
{
    public function _getFieldsetsParameters() : array
    {
        $result = parent::_getFieldsetsParameters();

        $result['package']['fields']['supplier_id']['type'] = 'text';
        $result['package']['fields']['supplier_id']['visible'] = false;

        return $result;
    }
}
