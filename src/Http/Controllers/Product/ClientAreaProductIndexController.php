<?php

namespace IlBronza\Products\Http\Controllers\Product;

use App\Models\ProductsPackage\Order;
use App\Models\ProductsPackage\OrderProduct;
use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;
use Illuminate\Http\Request;

use Carbon\Carbon;
use function config;

class ClientAreaProductIndexController extends ProductCRUD
{
	use CRUDEditUpdateTrait;
	use CRUDIndexTrait;

	public $allowedMethods = ['index', 'update'];
	public $avoidCreateButton = true;

	public function getIndexFieldsArray()
	{
		//ClientAreaProductFieldsGroupParametersFile
		return config('products.models.product.fieldsGroupsFiles.clientArea')::getFieldsGroup();
	}

	public function index(Request $request, string $client)
	{
		$this->client = Client::gpc()::find($client);

		return $this->_index($request);
	}

	public function getIndexElements()
	{
		// $ordersIds = Order::gpc()::select('id')->sorting()->whereHas('extraFields', function($query)
		// 	{
		// 		$query->where('due_date', '>', Carbon::now()->subDays(5));
		// 		$query->whereNull('loaded_at');
		// 	})->whereNull('completed_at')->pluck('id');

		// $productIds = OrderProduct::gpc()::whereIn('order_id', $ordersIds)->select('product_id')->pluck('product_id');

		return $this->getModelClass()::byClient($this->client)
				// ->whereIn('id', $productIds)
				->get();
	}

	public function getEditParametersFile() : ? string
	{
		//ClientAreaProductEditFieldsetsParameters
		return config('products.models.product.parametersFiles.clientArea.edit');
	}

	public function update(Request $request, string $client, string $product)
	{
		$product = $this->findModel($product);

//		if($orderProduct->getClient()->getKey() != $client)
//			abort(403);

		return $this->_update($request, $product);
	}
}
