<?php

namespace IlBronza\Products\Http\Controllers\Order;

use App\Providers\Helpers\OrderHtmlHelper;
use Illuminate\Http\Response;

class OrderHtmlController extends OrderCRUD
{
	public $allowedMethods = ['pdf'];

	public function pdf($order)
	{
		$order = $this->findModel($order);

		$helper = new OrderHtmlHelper($order);

		return response($helper->render(), 200, [
			'Content-Type' => 'text/html; charset=UTF-8',
		]);
	}
}
