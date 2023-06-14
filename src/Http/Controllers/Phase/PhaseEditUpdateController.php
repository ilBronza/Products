<?php

namespace IlBronza\Products\Http\Controllers\Phase;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;

class PhaseEditUpdateController extends PhaseCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.phase.parametersFiles.edit');
    }

    public function edit($phase)
    {
        $phase = $this->findModel($phase);

        return $this->_edit($phase);
    }

    public function update(Request $request, $phase)
    {
        $phase = $this->findModel($phase);

        return $this->_update($request, $phase);
    }
}
