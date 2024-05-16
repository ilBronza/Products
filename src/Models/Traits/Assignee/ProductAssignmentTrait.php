<?php

namespace IlBronza\Products\Models\Traits\Assignee;

use IlBronza\AccountManager\Models\User;
use IlBronza\Products\Models\OrderProductPhase;
use Illuminate\Support\Facades\DB;

trait ProductAssignmentTrait
{
    public function getClassnameAbbreviation() : string
    {
        return $this->classnameAbbreviation;
    }

    public function removeAssignees()
    {
        DB::table(config('products.models.assigneeTarget.table'))->where('type', $this->getClassnameAbbreviation())->where('target_id', $this->getKey())->delete();
    }

    public function associateAssignees($assignees)
    {
        $pivotContent = [];

        foreach($assignees  as $assignee)
            $pivotContent[$assignee] = ['type' => $this->getClassnameAbbreviation()];

        $this->assignees()->attach($pivotContent);
    }

    public function assigneesTargets()
    {
        return $this->belongsTo(
            User::getProjectClassName(),
            config('products.models.assigneeTarget.table'),
            'target_id',
            'user_id'
        )->where('type', $this->getClassnameAbbreviation());        
    }

    public function assignees()
    {
        return $this->belongsToMany(
            User::getProjectClassName(),
            config('products.models.assigneeTarget.table'),
            'target_id',
            'user_id'
        )->where('type', $this->getClassnameAbbreviation());
    }
}