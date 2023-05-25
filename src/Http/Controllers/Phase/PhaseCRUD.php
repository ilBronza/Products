<?php

namespace IlBronza\Products\Http\Controllers\Phase;

use IlBronza\CRUD\CRUD;

class PhaseCRUD extends CRUD
{
    public function setModelClass()
    {
        $this->modelClass = config('products.models.phase.class');
    }

}
