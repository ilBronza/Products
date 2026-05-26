<?php

namespace IlBronza\Products\Http\Controllers\Sellable;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class SellableEditUpdateController extends SellableCRUD
{
    use CRUDEditUpdateTrait;

    public ?bool $updateEditor = false;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.sellable.parametersFiles.edit');
    }

    public function edit(string $sellable)
    {
        $sellable = $this->findModel($sellable);

        return $this->_edit($sellable);
    }

    public function update(Request $request, $sellable)
    {
        $sellable = $this->findModel($sellable);

        return $this->_update($request, $sellable);
    }

    public function getAfterUpdatedRedirectUrl()
    {
        $type = $this->getModel()->getType();

        return app('products')->route('sellables.byType', [
            'type' => $type
        ]);
    }
}
