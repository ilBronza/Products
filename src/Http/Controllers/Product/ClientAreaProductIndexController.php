<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;
use Illuminate\Http\Request;
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
		return $this->getModelClass()::byClient($this->client)->get();
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
