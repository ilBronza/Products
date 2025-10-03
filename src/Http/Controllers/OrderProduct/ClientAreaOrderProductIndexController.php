<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use IlBronza\Clients\Models\Client;
use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Models\Order;
use Carbon\Carbon;

use Illuminate\Http\Request;

use function config;

class ClientAreaOrderProductIndexController extends OrderProductCRUD
{
	use CRUDEditUpdateTrait;
	use CRUDIndexTrait;

	public $allowedMethods = ['index', 'update'];
	public $avoidCreateButton = true;

	public function getIndexFieldsArray()
	{
		return config('products.models.orderProduct.fieldsGroupsFiles.clientArea')::getFieldsGroup();
	}

	public function index(Request $request, string $client)
	{
		$this->client = Client::gpc()::find($client);

		return $this->_index($request);
	}

	public function getIndexElements()
	{
		$orderIds = Order::gpc()::whereHas('extraFields', function($query)
			{
				$query->where('due_date', '>', Carbon::now()->subDays(14));
			})->select('id')->byClient($this->client)->activeOrNotShipped()->get();

		return $this->getModelClass()::whereIn('order_id', $orderIds)->get();
	}

	public function getEditParametersFile() : ? string
	{
		return config('products.models.orderProduct.parametersFiles.clientArea.edit');
	}

	public function update(Request $request, string $client, string $orderProduct)
	{
		$orderProduct = $this->findModel($orderProduct);

//		if($orderProduct->getClient()->getKey() != $client)
//			abort(403);

		return $this->_update($request, $orderProduct);
	}
}
