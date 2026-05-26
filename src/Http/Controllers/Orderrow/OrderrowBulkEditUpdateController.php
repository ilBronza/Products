<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDBulkEditTrait;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use Illuminate\Http\Request;
use function config;

class OrderrowBulkEditUpdateController extends OrderrowEditUpdateController
{
    use CRUDBulkEditTrait;

    public $allowedMethods = ['bulkEdit', 'bulkUpdate'];

    public function getOverriddenEditParametersFile() : string
    {
	    return config('products.models.orderrow.parametersFiles.bulkEdit');
    }

    public function getBulkUpdateModelAction() : string
    {
        return app('products')->route('orderrows.bulkUpdate');
    }

    public function bulkUpdate(Request $request)
    {
        $this->setKeys($request);

        // $keyName = $this->getPlaceholderModel()->getKeyName();

        // $models = $this->getModelClass()::whereIn(
        //     $keyName,
        //     $this->getKeys()
        // )->get();

        $models = RowsFinderHelper::getCompositeRowCollectionByIds($this->getKeys());

        foreach($models as $model)
            $this->_update($request, $model);

        return redirect()->route('iframe.close');
    }

}