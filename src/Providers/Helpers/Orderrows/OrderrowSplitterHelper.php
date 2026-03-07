<?php

namespace IlBronza\Products\Providers\Helpers\Orderrows;

use Carbon\Carbon;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Support\Facades\DB;

class OrderrowSplitterHelper
{
	/**
	 * Crea un nuovo timeline item che inizia 1 giorno dopo la fine dell'item originale
	 * e dura 1 giorno. L'item originale resta intoccato.
	 */
	public static function split(Orderrow $orderrow) : void
	{
		$endsAt = $orderrow->getEndsAt();

		if (!$endsAt) {
			throw new \InvalidArgumentException('L\'item deve avere una data di fine per poter essere diviso.');
		}

		DB::transaction(function () use ($orderrow, $endsAt) {
			$order = $orderrow->getOrder();
			$currentIndex = (int) ($orderrow->sorting_index ?? 0);

			$newStartsAt = Carbon::instance($endsAt)->addDay();
			$newEndsAt = $newStartsAt->copy()->addDay();

			$newRow = $orderrow->replicate();
			$newRow->starts_at = $newStartsAt;
			$newRow->ends_at = $newEndsAt;
			$newRow->sorting_index = $currentIndex + 1;
			$newRow->save();

			$order->orderrows()
				->where('sorting_index', '>=', $newRow->sorting_index)
				->where('id', '!=', $newRow->getKey())
				->increment('sorting_index');
		});
	}
}
