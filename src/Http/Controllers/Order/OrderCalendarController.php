<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Products\Http\Controllers\Order\OrderCRUD;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Quotations\Quotation;
use Illuminate\Http\Request;

class OrderCalendarController extends OrderCRUD
{
	public $allowedMethods = [
		'index',
		'getEventsByDates'
	];

	public function index()
	{
		$actionUrl = app('products')->route('orders.calendar.getEventsByDates');

		return view('crud::calendar.calendar', compact('actionUrl'));
	}

	public function getEventsByDates(Request $request)
	{
		$start = $request->get('start');
		$end = $request->get('end');

		$orders = Order::gpc()::byDateRange($start, $end)->get();
		$quotations = Quotation::gpc()::byDateRange($start, $end)->get();

		$events = [];

		foreach($orders as $order)
			$events[] = $order->toCalendarEvent();

		foreach($quotations as $quotation)
			$events[] = $quotation->toCalendarEvent();

		return response()->json($events);
	}
}
