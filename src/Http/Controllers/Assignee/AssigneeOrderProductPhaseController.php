<?php

namespace IlBronza\Products\Http\Controllers\Assignee;

use App\Workstation;
use IlBronza\AccountManager\Models\User;
use IlBronza\CRUD\CRUD;
use IlBronza\Products\Models\OrderProductPhase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssigneeOrderProductPhaseController extends CRUD
{
    public $allowedMethods = [
        'index',
        'workstationFetcher',
        'assigneesPopup',
        'setAssignees',
        'removeAssignees'
    ];

    public function removeAssignees(Request $request)
    {
        $request->validate([
            'orderProductPhases' => 'array|exists:' . config('products.models.orderProductPhase.table') .',id'
        ]);

        foreach($request->orderProductPhases as $orderProductPhaseId)
        {
            $orderProductPhase = OrderProductPhase::getProjectClassName()::find($orderProductPhaseId);

            if(! $orderProductPhase)
                continue;

            $orderProductPhase->removeAssignees();
        }

        return [
            'success' => true
        ];
    }


    public function setAssignees(Request $request)
    {
        $request->validate([
            'orderProductPhases' => 'array|exists:' . config('products.models.orderProductPhase.table') .',id',
            'operators' => 'array|exists:users,id',
        ]);

        foreach($request->orderProductPhases as $orderProductPhaseId)
        {
            $orderProductPhase = OrderProductPhase::getProjectClassName()::find($orderProductPhaseId);

            if(! $orderProductPhase)
                continue;

            $orderProductPhase->associateAssignees($request->operators);
        }

        return [
            'success' => true
        ];
    }

    public function workstationFetcher(string $workstation)
    {
        $workstation = Workstation::find($workstation);

        $workstationsAliases = DB::table('workstations')->whereNull('deleted_at')->select('alias')->where('common_workstation', $workstation->alias)->get();

        $unsortedOrderProductPhases = OrderProductPhase::getProjectClassName()::byWorkstationsIds(
                $workstationsAliases->pluck('alias')
            )
            ->whereHas('order')
            ->notCompleted()->sorted()->with(
                'extraFields',
                'order.client',
                'product',
                'assignees'
            )
            ->get();

        $orderProductPhases = $unsortedOrderProductPhases
            ->sort(function ($a, $b)
            {
                if (!$a->sorting_index) {
                    return !$b->sorting_index ? 0 : 1;
                }
            
                if (!$b->sorting_index)
                {
                    return -1;
                }
                if ($a->sorting_index == $b->sorting_index)
                {
                    return 0;
                }

                return $a->sorting_index < $b->sorting_index ? -1 : 1;
            });

        return view('products::assignees._workstation', compact('workstation', 'orderProductPhases'));
    }

    public function index()
    {
        $workstationsAliases = DB::table('workstations')->whereNull('deleted_at')->select('common_workstation')->distinct()->get();

        $workstations = Workstation::getProjectClassName()::whereNull('deleted_at')->orderBy('sorting_index')->whereIn('alias', $workstationsAliases->pluck('common_workstation'))->get();

        return view('products::assignees.index', compact('workstations'));
    }

    public function assigneesPopup()
    {
        $workers = User::getProjectClassName()::role('worker')
                    ->with('assignedOrderProductPhases')
                    ->with(['processings' => function($query)
                    {
                        $query->working()->today();
                    }])
                    ->get();

        return view('products::assignees.assigneesPopup', compact('workers'));
    }
}
