<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;
use IlBronza\Products\Models\OrderProductPhase;
use Illuminate\Http\Request;

class ElaboratedByWorkstationOrderProductPhaseIndexController extends OrderProductPhaseCRUD
{
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    public function getIndexFieldsArray()
    {
        return config('products.models.orderProductPhase.fieldsGroupsFiles.elaborated')::getFieldsGroup();
    }

    public function index(Request $request, string $workstation)
    {
        ini_set('max_execution_time', "300");
        ini_set('memory_limit', "-1");

        $this->workstation = $workstation;

        return $this->_index($request);
    }

    public function getIndexElements()
    {
        return OrderProductPhase::getProjectClassName()::byWorkstationId(
            $this->workstation
        )->with([
            'notes',
            'order',
            'timing',
            'timingestimation',
            'orderProduct' => function($query)
            {
                $query->withCount('reminds');
            }
        ])
        ->withCount('processings')->get();
    }
}
