<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\Products\Models\Interfaces\RowInterface;
use Illuminate\Http\Request;

class OrderrowEditUpdateController extends OrderrowCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getStandardParametersFile() : string
    {
        if($file = config("products.models.orderrow.parametersFiles.editByType." . $this->getModel()->getType()))
            return $file;

        return config('products.models.orderrow.parametersFiles.edit');
    }

    public function getOverriddenEditParametersFile() : string
    {
        if(! $sellableTarget = $this->getModel()?->getSellable()?->getTarget())
            return $this->getStandardParametersFile();

        if(! $packagePrefix = $sellableTarget->getPackageConfigPrefix())
            return $this->getStandardParametersFile();

        if($result = config("{$packagePrefix}.models.orderrow.parametersFiles.as.{$sellableTarget->getModelConfigPrefix()}.edit"))
            return $result;

        return config("{$packagePrefix}.models.orderrow.parametersFiles.edit");
    }

    public function setSpecificRow(RowInterface $row) : RowInterface
    {
        // $classMethod = "rowRelationBy{$row->getType()}";

        // $modelContainer = $row->getModelContainer();

        $typedRow = $row->getSpecificRow();

        $this->setModel($typedRow);

        return $typedRow;
    }

    public function edit(string $orderrow)
    {
        $orderrow = $this->findModel($orderrow);

        $specificRow = $this->setSpecificRow($orderrow);

        return $this->_edit($specificRow);
    }

    public function update(Request $request, $orderrow)
    {
        $orderrow = $this->findModel($orderrow);

        $specificRow = $this->setSpecificRow($orderrow);

        return $this->_update($request, $specificRow);
    }
}
