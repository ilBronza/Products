<?php

namespace IlBronza\Products\Http\Controllers\Project;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;

class ProjectCreateStoreController extends ProjectCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = [
        'create',
        'store',
    ];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.project.parametersFiles.create');
    }
}
