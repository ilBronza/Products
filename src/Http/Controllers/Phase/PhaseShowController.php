<?php

namespace IlBronza\Products\Http\Controllers\Phase;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\Phase\PhaseCRUD;

class PhaseShowController extends PhaseCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function show($phase)
    {
        $phase = $this->findModel($phase);

        return $this->_show($phase);
    }
}
