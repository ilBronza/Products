<?php

namespace IlBronza\Products\Http\Controllers\Project;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class ProjectDestroyController extends ProjectCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($project)
    {
        $project = $this->findModel($project);

        return $this->_destroy($project);
    }
}