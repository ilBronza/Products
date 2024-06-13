<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class QuotationrowRelatedFieldsGroupParametersFile extends QuotationrowFieldsGroupParametersFile
{
    static function getFieldsGroup() : array
    {
        $result = parent::getFieldsGroup();

        return $result;
    }
}