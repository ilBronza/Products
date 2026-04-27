<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\FormField\FormField;
use IlBronza\Form\Form;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function array_keys;
use function compact;
use function dd;
use function implode;
use function route;
use function trans;
use function view;

class OrderAddOrderrowIndexController extends OrderCRUD
{
	public $allowedMethods = ['addOrderrow', 'storeRow'];

	public function addOrderrow(Request $request, $order, string $type)
	{
		$type = ucfirst($type);

		if($request->table)
			return redirect()->to(app('products')->route('orders.addOrderrowsByTable', ['order' => $order, 'type' => $type]));

		if($request->isMethod('get'))
			session()->put('orderAddOrderrowIndexController_storeRow_return_url', url()->previous());

		$order = $this->findModel($order);

		$parameters = $request->validate([
			'ids' => 'array|nullable|exists:' . Sellable::gpc()::make()->getTable() . ',id',
		]);

		$types = [
			$type
		];

		$form = Form::createFromArray([
			'action' => $order->getStoreRowUrl(),
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
		return RowsFinderHelper::getSortingIndexByType($order, $type);
	}

	public function storeRow(Request $request, $order)
	{
		$order = $this->findModel($order);

		$types = Sellable::gpc()::query()
			->select('type')
			->distinct()
			->orderBy('type')
			->pluck('type')
			->filter()
			->all();

		$validationParameters = [];

		foreach ($types as $type)
		{
			$validationParameters[$type] = 'array|nullable';
			$validationParameters[$type . '.*.sellable'] = 'string|required|in:' . implode(',', array_keys($order->getPossibleSellablesByType($type)));
			$validationParameters[$type . '.*.quantity'] = 'integer|required|min:1';
		}

		$validator = Validator::make($request->all(), $validationParameters);

		if ($validator->fails())
		{
		    dd([
		        'request' => $request->all(),
		        'errors' => $validator->errors()->toArray(),
		        'firstError' => $validator->errors()->first(),
		    ]);
		}

		$parameters = $validator->validated();

		if (count($parameters) == 0)
		{
		    dd([
		        'message' => 'Manca la chiave per questo tipo',
		        'request' => $request->all(),
		    ], $parameters, $validationParameters, $request->all());
		}

		//		dd($request->all());
		//		dd($validationParameters);

		// $parameters = $request->validate($validationParameters);

		if(count($parameters) == 0)
			dd(['Manca la chiave per questo tipo', $request->all()]);

//		$orderrowSortingIndex = $order->orderrows()->max('sorting_index') + 1;
//
		foreach ($parameters as $type => $sellables)
		{
			$orderrowSortingIndex = $this->getSortingIndexByType($order, $type);

			foreach ($sellables as $key => $_parameters)
			{
				$sellable = Sellable::gpc()::find($_parameters['sellable']);

				for ($i = 0; $i < $_parameters['quantity']; $i ++)
				{
					$result = RowAssociatorHelper::associateRowBySellable($order, $sellable);
				}
			}
		}

		if($url = session()->get('orderAddOrderrowIndexController_storeRow_return_url'))
		{
			session()->forget('orderAddOrderrowIndexController_storeRow_return_url');

			return redirect()->to($url);
		}

		return view('datatables::utilities.closeIframe', ['reloadAllTables' => true]);

		// return redirect()->to(
		// 	$order->getEditUrl()
		// );
	}

}

