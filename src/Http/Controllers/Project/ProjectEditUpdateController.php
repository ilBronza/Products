<?php

namespace IlBronza\Products\Http\Controllers\Project;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class ProjectEditUpdateController extends ProjectCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.project.parametersFiles.create');
    }

    public function edit(string $project)
    {
        $project = $this->findModel($project);

        return $this->_edit($project);
    }

    public function update(Request $request, $project)
    {
        $project = $this->findModel($project);

        return $this->_update($request, $project);
    }
}
