<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use Carbon\Carbon;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use function array_rand;
use function cache;

class GlobalOrderTimelineController extends GlobalTimelineController
{
	public function getEndpoint() : string
	{
		return app('products')->route('orders.globalTimeline');
	}

	public function getRows() : Collection
	{
		return Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->where('ends_at', '>', Carbon::now()->subDays(14))->get();
	}

	public function getGroupSubject(Orderrow $row) : Model
	{
		return $row->getOrder();
	}

}
