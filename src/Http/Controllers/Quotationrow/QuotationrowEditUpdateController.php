<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\Products\Models\Interfaces\RowInterface;
use Illuminate\Http\Request;

class QuotationrowEditUpdateController extends QuotationrowCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getStandardParametersFile() : string
    {
        return config('products.models.quotationrow.parametersFiles.edit');        
    }

    public function getOverriddenEditParametersFile() : string
    {
        if(! $sellableTarget = $this->getModel()->getSellable()->getTarget())
            return $this->getStandardParametersFile();

        if(! $packagePrefix = $sellableTarget->getPackageConfigPrefix())
            return $this->getStandardParametersFile();

        return config("{$packagePrefix}.models.quotationrow.parametersFiles.edit");
    }

    public function setSpecificRow(RowInterface $row) : RowInterface
    {
        $classMethod = "rowRelationBy{$row->getType()}";

        $modelContainer = $row->getModelContainer();

        $typedRow = $modelContainer->{$classMethod}()->find($row->getKey());

        $this->setModel($typedRow);

        return $typedRow;
    }

    public function edit(string $quotationrow)
    {
        $quotationrow = $this->findModel($quotationrow);

        $specificRow = $this->setSpecificRow($orderrow);

        return $this->_edit($specificRow);
    }

    public function update(Request $request, $quotationrow)
    {
        $quotationrow = $this->findModel($quotationrow);

        $specificRow = $this->setSpecificRow($orderrow);

        return $this->_update($request, $specificRow);
    }
}
