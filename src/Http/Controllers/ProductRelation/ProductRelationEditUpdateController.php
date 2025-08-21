<?php

namespace IlBronza\Products\Http\Controllers\ProductRelation;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use IlBronza\CRUD\Traits\CRUDPivotControllerTrait;
use IlBronza\CRUD\Traits\Model\CRUDModelTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use Illuminate\Http\Request;

class ProductRelationEditUpdateController extends ProductRelationCRUD
{
    use CRUDEditUpdateTrait;
	use CRUDPivotControllerTrait {
		CRUDPivotControllerTrait::getBackToListButton insteadof CRUDEditUpdateTrait;
	}

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.productRelation.parametersFiles.edit');
    }

    public function getAfterUpdatedRedirectUrl()
    {
        return $this->getModel()->getParent()->getShowUrl();
    }

    public function edit($productRelation)
    {
        $productRelation = $this->findModel($productRelation);

        return $this->_edit($productRelation);
    }

    public function update(Request $request, $productRelation)
    {
        $productRelation = $this->findModel($productRelation);

        return $this->_update($request, $productRelation);
    }
}
