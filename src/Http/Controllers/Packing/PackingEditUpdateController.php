<?php

namespace IlBronza\Products\Http\Controllers\Packing;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;
use IlBronza\Products\Http\Controllers\Packing\PackingCRUD;

class PackingEditUpdateController extends PackingCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.packing.parametersFiles.edit');
    }

    public function edit($packing)
    {
        $packing = $this->findModel($packing);

        return $this->_edit($packing);
    }

    public function update(Request $request, $packing)
    {
        $packing = $this->findModel($packing);

        return $this->_update($request, $packing);
    }
}
