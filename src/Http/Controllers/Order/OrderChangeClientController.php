<?php

namespace IlBronza\Products\Http\Controllers\Order;

use Illuminate\Http\Request;

use function config;

class OrderChangeClientController extends OrderEditUpdateController
{
	public ?bool $updateEditor = false;

	public $allowedMethods = ['edit', 'update'];

	public function getEditParametersFile() : ?string
	{
		return config('products.models.order.parametersFiles.changeClient');
	}

	public function edit($order)
	{
		$order = $this->findModel($order);

		return $this->_edit($order);
	}

	public function getRelationshipsManagerClass()
	{
		return null;
	}

	public function getUpdateModelAction()
	{
		return $this->getModel()->getKeyedRoute('changeClientUpdate');
	}

	public function update(Request $request, $order)
	{
		$order = $this->findModel($order);

		$order->project_id = null;

		return $this->_update($request, $order);
	}

	public function getAfterUpdateRoute()
	{
		return $this->getModel()->getEditUrl();
	}
}
