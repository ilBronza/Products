<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Form\Form;
use IlBronza\FormField\FormField;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\UikitTemplate\Fetcher;

use Illuminate\Http\Request;

use function array_keys;
use function compact;
use function dd;
use function implode;
use function route;
use function trans;
use function view;

class OrderAddOrderrowIndexController extends OrderCRUD
{
	public $allowedMethods = ['addOrderrow', 'storeOrderrow'];

	public function addOrderrow($order, string $type)
	{
		$order = $this->findModel($order);

		$types = [
			$type
		];

		$form = Form::createFromArray([
			'action' => $order->getStoreOrderrowUrl(),
			'method' => 'POST'
		]);

		foreach ($types as $type)
			$form->addFormField(
				FormField::createFromArray([
					'name' => $type,
					'label' => trans('products::sellables.pickSellables'),
					'type' => 'json',
					'rules' => 'array|required',
					'fields' => [
						'sellable' => [
							'name' => 'sellable_' . $type,
							'label' => trans('products::sellables.sellable'),
							'type' => 'select',
							'select2' => false,
							'rules' => 'string|required|in:' . implode(',', array_keys($order->getPossibleSellablesByType($type))),
							'list' => $order->getPossibleSellablesByType($type)
						],
						'quantity' => [
							'label' => trans('products::fields.quantity'),
							'type' => 'number',
							'rules'=> 'integer|required|min:1'
						]
					]
				])
			);

		return view('form::uikit.form', ['form' => $form]);
	}

	public function storeOrderrow(Request $request, $order)
	{
		$order = $this->findModel($order);

		$types = [
			'Contracttype',
			'VehicleType',
			'Surveillance',
			'Hotel',
			'Rent',
			'Reimbursement'
		];

		$validationParameters = [];

		foreach ($types as $type)
		{
			$validationParameters[$type] = 'array|nullable';
			$validationParameters[$type . '.*.sellable'] = 'string|required|in:' . implode(',', array_keys($order->getPossibleSellablesByType($type)));
			$validationParameters[$type . '.*.quantity'] = 'integer|required|min:1';
		}


		//		dd($request->all());
		//		dd($validationParameters);

		$parameters = $request->validate($validationParameters);

		if(count($parameters) == 0)
			dd(['Manca la chiave per questo tipo', $request->all()]);

		$orderrowSortingIndex = $order->orderrows()->max('sorting_index') + 1;

		foreach ($parameters as $type => $sellables)
		{
			foreach ($sellables as $key => $_parameters)
			{
				$sellable = Sellable::getProjectClassName()::find($_parameters['sellable']);

				for ($i = 0; $i < $_parameters['quantity']; $i ++)
				{
					$orderrow = Orderrow::getProjectClassName()::make();
					$orderrow->sellable()->associate($sellable);
					$orderrow->order()->associate($order);

					$orderrow->sorting_index = $orderrowSortingIndex ++;

					$orderrow->save();
				}
			}
		}

		return view('datatables::utilities.closeIframe', ['reloadAllTables' => true]);

		return redirect()->to(
			$order->getEditUrl()
		);
	}

}

