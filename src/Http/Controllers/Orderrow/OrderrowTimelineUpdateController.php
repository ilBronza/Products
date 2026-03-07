<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use Carbon\Carbon;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowCRUD;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderrowTimelineUpdateController extends OrderrowCRUD
{
    public $allowedMethods = ['update'];

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'item.id' => 'required|exists:' . Orderrow::gpc()::make()->getTable() . ',id',
			'item.start' => [
				'required',
				'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/'
			],
			'item.end' => [
				'required',
				'after:item.start',
				'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/'
			],
		]);

		if($validator->fails())
			return [
				'success' => false,
				'errors' => $validator->errors(),
			];

		$orderrow = $this->findModel($request->input('item.id'));

		$item = $request->item;

		$startDate = Carbon::parse($item['start'])->setTimezone(config('app.timezone'));
		$endDate = Carbon::parse($item['end'])->setTimezone(config('app.timezone'));

		$orderrow->starts_at = $startDate;
		$orderrow->ends_at = $endDate;

		$orderrow->save();

		return [
			'success' => true,
			'message' => 'Elemento ' . $orderrow->getName() . ' aggiornato'
		];
	}	
}
