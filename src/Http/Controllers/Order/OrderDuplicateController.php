<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Form\Form;
use IlBronza\FormField\FormField;
use IlBronza\Products\Http\Controllers\Traits\RowcontainerDuplicateControllerTrait;
use Illuminate\Http\Request;

use function config;

class OrderDuplicateController extends OrderCRUD
{
	use RowcontainerDuplicateControllerTrait;

	public $allowedMethods = ['duplicateForm', 'selectRelations', 'duplicate'];

	protected function getRowcontainerModelConfigPrefix() : string
	{
		return 'order';
	}

	public function duplicateForm($order)
	{
		$order = $this->findModel($order);

		$form = Form::createFromArray([
			'action' => $order->getKeyedRoute('selectRelations'),
			'method' => 'POST'
		]);

		$form->setCard();
		$form->setTitle(trans('products::orders.duplicateOrder', ['order' => $order->getName()]));

		$form->addCardClasses(['uk-width-large']);

		$form->addFormField(
			FormField::createFromArray([
				'name' => 'event_starts_at',
				'label' => 'Data inizio evento',
				'type' => 'date'
			]));

		return $form->render();
	}

	public function selectRelations(Request $request, $order)
	{
		$requestParameters = $this->validateDuplicateDateRequest($request);

		$order = $this->findModel($order);

		return $this->renderSelectRelationsView(
			$order,
			$requestParameters['event_starts_at'] ?? null,
			$order->getKeyedRoute('duplicate')
		);
	}

	public function duplicate(Request $request, $order)
	{
		$requestParameters = $this->validateDuplicateRequest($request);

		$order = $this->findModel($order);

		$helperClass = config('products.models.order.helpers.duplicate');

		$helper = new $helperClass($order, $requestParameters);

		$duplicatedOrder = $helper->duplicate();

		return redirect()->to($duplicatedOrder->getEditUrl());
	}
}
