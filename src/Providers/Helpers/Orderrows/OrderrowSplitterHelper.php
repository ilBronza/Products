<?php

namespace IlBronza\Products\Providers\Helpers\Orderrows;

use Carbon\Carbon;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Support\Facades\DB;

class OrderrowSplitterHelper
{
	const DUPLICATE_MAX_DURATION_HOURS = 48;

	/**
	 * Durata <= 48h: duplica la riga accodandola dopo la fine dell'originale,
	 * con la stessa durata. L'originale resta intoccato.
	 *
	 * Durata > 48h: taglio vero. L'originale viene accorciato alla fine del
	 * giorno di mezzo (punto temporale di mezzo arrotondato per eccesso a
	 * fine giornata) e la parte restante diventa una nuova riga.
	 */
	public static function split(Orderrow $orderrow) : void
	{
		$startsAt = $orderrow->getStartsAt();
		$endsAt = $orderrow->getEndsAt();

		if (!$startsAt || !$endsAt) {
			throw new \InvalidArgumentException('L\'item deve avere data di inizio e di fine per poter essere diviso.');
		}

		$startsAt = Carbon::instance($startsAt);
		$endsAt = Carbon::instance($endsAt);

		if ($startsAt->diffInHours($endsAt) <= static::DUPLICATE_MAX_DURATION_HOURS) {
			static::duplicateAfter($orderrow, $startsAt, $endsAt);

			return;
		}

		static::splitAtMiddleDay($orderrow, $startsAt, $endsAt);
	}

	protected static function duplicateAfter(Orderrow $orderrow, Carbon $startsAt, Carbon $endsAt) : void
	{
		DB::transaction(function () use ($orderrow, $startsAt, $endsAt) {
			$durationMinutes = $startsAt->diffInMinutes($endsAt);

			$newStartsAt = $endsAt->copy()->addDay();
			$newEndsAt = $newStartsAt->copy()->addMinutes($durationMinutes);

			static::createFollowingRow($orderrow, $newStartsAt, $newEndsAt);
		});
	}

	protected static function splitAtMiddleDay(Orderrow $orderrow, Carbon $startsAt, Carbon $endsAt) : void
	{
		DB::transaction(function () use ($orderrow, $startsAt, $endsAt) {
			// Punto di mezzo temporale, arrotondato per eccesso alla
			// mezzanotte successiva: il giorno di mezzo resta intero
			// nella prima parte.
			$middle = $startsAt->copy()->addMinutes(intdiv($startsAt->diffInMinutes($endsAt), 2));

			$cutoff = $middle->eq($middle->copy()->startOfDay())
				? $middle->copy()
				: $middle->copy()->startOfDay()->addDay();

			// Rete di sicurezza: il taglio deve cadere dentro l'intervallo.
			if ($cutoff->lte($startsAt) || $cutoff->gte($endsAt))
				$cutoff = $middle;

			static::createFollowingRow($orderrow, $cutoff->copy(), $endsAt->copy());

			$orderrow->ends_at = $cutoff;
			$orderrow->save();
		});
	}

	protected static function createFollowingRow(Orderrow $orderrow, Carbon $startsAt, Carbon $endsAt) : Orderrow
	{
		$order = $orderrow->getOrder();
		$currentIndex = (int) ($orderrow->sorting_index ?? 0);

		$newRow = $orderrow->replicate();
		$newRow->starts_at = $startsAt;
		$newRow->ends_at = $endsAt;
		$newRow->sorting_index = $currentIndex + 1;
		$newRow->save();

		$order->orderrows()
			->where('sorting_index', '>=', $newRow->sorting_index)
			->where('id', '!=', $newRow->getKey())
			->increment('sorting_index');

		return $newRow;
	}
}
