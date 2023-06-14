<?php

namespace IlBronza\Products\Http\Controllers;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

trait CRUDProductPackageShowTrait
{
    use CRUDRelationshipTrait;
    use CRUDShowTrait;

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function getGenericParametersFile() : ? string
    {
        return config("products.models.{$this->configModelClassName}.parametersFiles.show");
    }

}
