<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

use function config;

class OrderEditUpdateController extends OrderCRUD
{
	use CRUDEditUpdateTrait;

	public ?bool $updateEditor = true;

	public $allowedMethods = ['edit', 'update'];

	public function getRelationshipsManagerClass()
	{
		return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
	}

	public function getGenericParametersFile() : ?string
	{
		return config('products.models.order.parametersFiles.edit');
	}

	public function edit($order)
	{
		$order = $this->findModel($order);

		if (! $order->isFrozen())
			$this->addNavbarButton(
				$order->getResetRowsIndexesButton()
			);

		if (! $order->isFrozen())
			$this->addNavbarButton(
				$order->getFreezeButton()
			);

		return $this->_edit($order);
	}

	public function update(Request $request, $order)
	{
		$order = $this->findModel($order);

		return $this->_update($request, $order);
	}
}
