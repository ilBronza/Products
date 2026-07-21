<?php

namespace IlBronza\Products\Providers\Helpers\Orderrows;

use Carbon\Carbon;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Support\Facades\DB;

class OrderrowWeekendSplitterHelper extends OrderrowSplitterHelper
{
	/**
	 * Divide la riga togliendo i giorni di week-end: l'intervallo viene
	 * spezzato in segmenti di soli giorni feriali (i tagli cadono a
	 * mezzanotte tra venerdì e sabato e tra domenica e lunedì).
	 *
	 * L'originale diventa il primo segmento, gli altri diventano righe nuove.
	 */
	public static function split(Orderrow $orderrow) : void
	{
		$startsAt = $orderrow->getStartsAt();
		$endsAt = $orderrow->getEndsAt();

		if (!$startsAt || !$endsAt) {
			throw new \InvalidArgumentException('L\'item deve avere data di inizio e di fine per poter essere diviso.');
		}

		$segments = static::calculateWeekdaySegments(Carbon::instance($startsAt), Carbon::instance($endsAt));

		if (count($segments) === 0) {
			throw new \InvalidArgumentException('L\'item copre solo giorni di week-end: niente da dividere.');
		}

		DB::transaction(function () use ($orderrow, $segments) {
			$first = array_shift($segments);

			// In ordine inverso: ogni riga nuova entra subito dopo
			// l'originale e spinge avanti le sorelle già create,
			// così l'ordine finale rispecchia quello temporale.
			foreach (array_reverse($segments) as $segment)
				static::createFollowingRow($orderrow, $segment[0], $segment[1]);

			$orderrow->starts_at = $first[0];
			$orderrow->ends_at = $first[1];
			$orderrow->save();
		});
	}

	/**
	 * @return array<array{0: Carbon, 1: Carbon}>
	 */
	public static function calculateWeekdaySegments(Carbon $startsAt, Carbon $endsAt) : array
	{
		$segments = [];
		$cursor = $startsAt->copy();

		while ($cursor->lt($endsAt))
		{
			// Se il cursore cade nel week-end, salta al primo giorno feriale.
			while ($cursor->isWeekend())
				$cursor = $cursor->addDay()->startOfDay();

			if ($cursor->gte($endsAt))
				break;

			// Fine segmento: la mezzanotte del sabato successivo.
			$segmentEnd = $cursor->copy()->startOfDay();

			while (! $segmentEnd->isWeekend())
				$segmentEnd->addDay();

			if ($segmentEnd->gt($endsAt))
				$segmentEnd = $endsAt->copy();

			$segments[] = [$cursor->copy(), $segmentEnd->copy()];

			$cursor = $segmentEnd->copy();
		}

		return $segments;
	}
}
