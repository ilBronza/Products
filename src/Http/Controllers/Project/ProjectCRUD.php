<?php

namespace IlBronza\Products\Http\Controllers\Project;

use IlBronza\Products\Http\Controllers\CRUDProductPackageController;


class ProjectCRUD extends CRUDProductPackageController
{
	public ?bool $updateEditor = false;

    public $configModelClassName = 'project';
}
