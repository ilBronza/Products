<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use Illuminate\Http\Request;

class ToElaborateByWorkstationOrderProductIndexController extends OrderProductCRUD
{
    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return config('products.models.orderProduct.fieldsGroupsFiles.workstationRelated')::getFieldsGroup();
    }

    public function getIndexElements()
    {
        ini_set('max_execution_time', "120");
        ini_set('memory_limit', "-1");

        $result = $this->getModelClass()::query()
            ->completed()
            ->withClientId()
            ->withWaveAlias()
            // ->byCompletionPeriod($this->dateFrom, $this->dateTo)
            ->withSupplierId()
            ->withProductSizes()
            ->with('size')
            ->byWorkstationId($this->workstationId)
            ->get();

        return $result;
    }

    public function index(Request $request, string $workstation)
    {
        $this->workstationId = $workstation;

        return $this->_index($request);
    }
}
