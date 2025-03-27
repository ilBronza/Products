<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Form\Form;
use IlBronza\FormField\FormField;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;

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

	public function addOrderrow(Request $request, $order, string $type)
	{
		if($request->table)
			return redirect()->to(app('products')->route('orders.addOrderrowsByTable', ['order' => $order, 'type' => $type]));

		$order = $this->findModel($order);

		$parameters = $request->validate([
			'ids' => 'array|nullable|exists:' . Sellable::gpc()::make()->getTable() . ',id',
		]);

		$types = [
			$type
		];

		$form = Form::createFromArray([
			'action' => $order->getStoreOrderrowUrl(),
			'method' => 'POST'
		]);

		$value = [];

		foreach($request->ids ?? [] as $id)
			$value[] = [
				'sellable' => $id,
				'quantity' => 1
			];

		foreach ($types as $type)
		{
			$form->addFormField(
				FormField::createFromArray([
					'name' => $type,
					'label' => trans('products::sellables.pickSellables'),
					'type' => 'json',
					'rules' => 'array|required',
					'value' => $value,
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
		}

		return view('form::uikit.form', ['form' => $form]);
	}

	public function getSortingIndexByType($order, string $type)
	{
		if ($type == 'Contracttype')
			return $order->operatorRows()->max('sorting_index') + 1;

		if ($type == 'VehicleType')
			return $order->vehicleRows()->max('sorting_index') + 1;

		if ($type == 'Surveillance')
			return $order->surveillanceRows()->max('sorting_index') + 1;

		if ($type == 'Hotel')
			return $order->hotelRows()->max('sorting_index') + 1;

		if ($type == 'Rent')
			return $order->rentRows()->max('sorting_index') + 1;

		if ($type == 'service')
			return $order->rentRows()->max('sorting_index') + 1;

		if ($type == 'Reimbursement')
			return $order->reimbursementRows()->max('sorting_index') + 1;

		if ($type == 'ControlRoom')
			return $order->controlRoomRows()->max('sorting_index') + 1;

		dd('manca type ' . $type);
	}

	public function storeOrderrow(Request $request, $order)
	{
		$order = $this->findModel($order);

		$types = [
			'Contracttype',
			'VehicleType',
			'Surveillance',
			'Hotel',
			'service',
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

//		$orderrowSortingIndex = $order->orderrows()->max('sorting_index') + 1;
//
		foreach ($parameters as $type => $sellables)
		{
			$orderrowSortingIndex = $this->getSortingIndexByType($order, $type);

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

