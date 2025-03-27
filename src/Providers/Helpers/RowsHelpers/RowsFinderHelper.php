<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use Carbon\Carbon;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Quotations\Quotationrow;

use function dd;

class RowsFinderHelper
{
	static function findOrderrowsByDateRange(Carbon $startsAt, Carbon $endsAt)
	{
		return static::findByDateRange(Orderrow::gpc()::class, $startsAt, $endsAt);
	}

	static function findQuotationrowsByDateRange(Carbon $startsAt, Carbon $endsAt)
	{
		return static::findByDateRange(Quotationrow::gpc()::class, $startsAt, $endsAt);
	}

	static function findOrderrowsByDate(Carbon $date)
	{
		return static::findByDate(Orderrow::gpc(), $date);
	}

	static function findOrderrowsByDateQuery(Carbon $date)
	{
		return static::getFindByDateQuery(Orderrow::gpc(), $date);
	}

	static function findQuotationrowsByDateQuery(Carbon $date)
	{
		return static::getFindByDateQuery(Quotationrow::gpc(), $date);
	}

	static function findQuotationrowsByDate(Carbon $date)
	{
		return static::findByDate(Quotationrow::gpc()::class, $date);
	}

	static function findByDate(string $class, Carbon $date)
	{
		return static::getFindByDateQuery($class, $date)->get();
	}

	static function getFindByDateQuery(string $class, Carbon $date)
	{
		$placeholder = $class::make();

		$modelContainerClass = $placeholder->getModelContainerClass();

		$containerIds = $modelContainerClass::duringDate($date)->select('id')->pluck('id');

		$foreign = $placeholder->modelContainer()->getForeignKeyName();

		return $class::where(function ($query) use($foreign, $containerIds, $date)
		{
			$query->whereIn($foreign, $containerIds);

			$query->orWhere(function ($query) use($date)
			{
				$query->where('starts_at', '>=', $date);
				$query->where('ends_at', '<=', $date);
			});
		});
	}

	static function findByDateRange(string $class, Carbon $startsAt, Carbon $endsAt)
	{
		$placeholder = $class::make();

		dd($placeholder);
		return $class::where(function ($query)
		{
			$query->whereIn('order_id', $this->getOrdersIds);
			$query->orWhere(function ($query)
			{
				$query->where('starts_at', '>=', $this->getDateStart());
				$query->where('starts_at', '<=', $this->getDateEnd());
			})->orWhere(function ($query)
			{
				$query->where('ends_at', '>=', $this->getDateStart());
				$query->where('ends_at', '<=', $this->getDateEnd());
			})->orWhere(function ($query)
			{
				$query->where('starts_at', '<=', $this->getDateStart());
				$query->where('ends_at', '>=', $this->getDateEnd());
			});
		})->with('order.project')->orderBy('starts_at')->get();
	}
}