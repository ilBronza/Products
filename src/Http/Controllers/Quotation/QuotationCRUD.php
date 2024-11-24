<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\Products\Http\Controllers\CRUDProductPackageController;


class QuotationCRUD extends CRUDProductPackageController
{
	static $modelConfigPrefix = 'quotation';
    public $configModelClassName = 'quotation';
}
