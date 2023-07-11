<?php

namespace IlBronza\Products\Http\Controllers\Order;

use App\Models\Client\Client;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActiveByClientOrderIndexController extends OrderCRUD
{
    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return config('products.models.order.fieldsGroupsFiles.active')::getFieldsGroup();
    }

    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    public function getIndexElements()
    {
        return $this->getModelClass()::active()
            ->byClientId($this->client->getKey())
            ->get();
    }

    public function index(Request $request, Client $client)
    {
        $this->client = $client;

        return $this->_index($request);
    }

}
