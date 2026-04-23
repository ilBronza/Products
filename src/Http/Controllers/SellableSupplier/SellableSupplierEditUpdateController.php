<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

use function config;
use function dd;

class SellableSupplierEditUpdateController extends SellableSupplierCRUD
{
    use CRUDEditUpdateTrait;

	public ?bool $updateEditor = false;

	public $returnBack = true;

    public $allowedMethods = ['edit', 'update'];

    public function getOverriddenEditParametersFile() : string
    {
        if(! $sellableTarget = $this->getModel()->getSellable()->getTarget())
            return $this->getStandardParametersFile();

        if(! $packagePrefix = $sellableTarget->getPackageConfigPrefix())
            return $this->getStandardParametersFile();

        if($file = config("{$packagePrefix}.models.sellableSupplier.parametersFiles.edit"))
        	return $file;

		return config('products.models.sellableSupplier.parametersFiles.edit');
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
