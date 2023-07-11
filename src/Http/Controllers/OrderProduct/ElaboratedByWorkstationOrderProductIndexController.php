<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use Illuminate\Http\Request;

class ElaboratedByWorkstationOrderProductIndexController extends OrderProductCRUD
{
    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;
    public $dateFrom;
    public $dateTo;

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
            ->byCompletionPeriod($this->dateFrom, $this->dateTo)
            ->withSupplierId()
            ->withProductSizes()
            ->byWorkstationId($this->workstationId)
            ->get();

        return $result;
    }

    public function index(Request $request, string $workstation, Carbon $dateFrom = null, Carbon $dateFo = null)
    {
        $this->workstationId = $workstation;

        if(! $this->dateFrom)
            $this->dateFrom = Carbon::now()->subYears(1);

        if(! $this->dateTo)
            $this->dateTo = Carbon::now();

        return $this->_index($request);
    }
}
