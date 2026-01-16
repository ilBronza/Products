<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use Carbon\Carbon;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class GlobalSellableTimelineController extends GlobalTimelineController
{
	public function getEndpoint() : string
	{
		return app('products')->route('sellables.globalTimeline');
	}

	public function getRows() : Collection
	{
		return Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->where('ends_at', '>', Carbon::now()->subDays(14))->get();
	}

	public function getGroupSubject(Orderrow $row) : Model
	{
		return $row->getSellable();
	}

	public function getGroupColorByRow(Orderrow $row) : string
	{
		return $row->getSellable()?->getTarget()?->getCssBackgroundColorValue() ?? '#cccccc';
	}

	public function getGroupTextColorByRow(Orderrow $row) : string
	{
		return $row->getSellable()?->getTarget()?->getCssTextColorValue() ?? $this->calculateTextColorFromBackgroundColor($this->getGroupColorByRow($row));
	}

}
