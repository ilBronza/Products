<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

use function config;
use function dd;

class SellableSupplierEditUpdateController extends SellableSupplierCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
		if(($sellable = $this->getModel()->getSellable())->isContracttype())
			return config('products.models.sellableSupplier.parametersFiles.contracttype');

		dd('duduhere');
	    dd('altro tipo di sellable: ' . $targetType);

        return config('products.models.sellableSupplier.parametersFiles.create');
    }

    public function edit(string $sellableSupplier)
    {
        $sellableSupplier = $this->findModel($sellableSupplier);

        return $this->_edit($sellableSupplier);
    }

    public function update(Request $request, $sellableSupplier)
    {
        $sellableSupplier = $this->findModel($sellableSupplier);

        return $this->_update($request, $sellableSupplier);
    }
}
