<?php

namespace IlBronza\Products\Http\Controllers\ClientArea;

use IlBronza\Clients\Models\Client;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Order\OrderCRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use function config;
use function ini_set;

class ClientAreaOrderIndexController extends OrderCRUD
{
    use CRUDIndexTrait;

	public $allowedMethods = ['index'];
	public $avoidCreateButton = true;

	public function getIndexFieldsArray()
    {
        return config('products.models.order.fieldsGroupsFiles.clientArea')::getTracedFieldsGroup();
    }

	public function index(Request $request, string $client)
	{
		$this->client = Client::gpc()::find($client);

		return $this->_index($request);
	}

    public function getIndexElements()
    {
	    return $this->getModelClass()::byClient($this->client)->activeOrNotShipped()->get();
    }

}
