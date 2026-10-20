<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use App\Http\Controllers\CustomRows\Hotel\HotelOrderrow;
use App\Http\Controllers\CustomRows\Hotel\HotelQuotationrow;
use App\Http\Controllers\CustomRows\Reimbursement\ReimbursementOrderrow;
use App\Http\Controllers\CustomRows\Reimbursement\ReimbursementQuotationrow;
use Carbon\Carbon;
use IlBronza\Operators\Models\Operator;
use IlBronza\Operators\Models\Sellables\OperatorOrderrow;
use IlBronza\Operators\Models\Sellables\OperatorQuotationrow;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\ProductOrderrow;
use IlBronza\Products\Models\Sellables\ProductQuotationrow;
use IlBronza\Vehicles\Models\Sellables\VehicleOrderrow;
use IlBronza\Vehicles\Models\Sellables\VehicleQuotationrow;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use function dd;

class RowsFinderHelper
{
	static function addOperatorOrderrowRelationships(Builder $query)
	{
		if(Operator::gpc()::make()->getExtraFieldsClass())
			$query->with('sellableSupplier.supplier.target.operator.extraFields');
		else
			$query->with('sellableSupplier.supplier.target.operator');

		if(method_exists(OperatorOrderrow::gpc()::make(), 'clientOperator'))
			$query->with('clientOperator.client');

		$query->with('sellableSupplier.supplier.target.operator.clientOperators.client');
		$query->with('sellableSupplier.supplier.target.operator.user.userdata');
		$query->with('sellableSupplier.supplier.target.operator.address');

		return $query;
	}

	static function getPossibleCustomQuotationrowsClasses() : array
	{
		$result = [];

		foreach(config('products.models.customQuotationrows') as $parameters)
			$result[] = $parameters['class'];

		return $result;
	}

	static function getPossibleCustomOrderrowsClasses() : array
	{
		$result = [];

		foreach(config('products.models.customOrderrows') as $parameters)
			$result[] = $parameters['class'];

		return $result;
	}

	static function getQuotationCustomSpecificRowById(int|string $id, array $relations = []) : ?Quotationrow
	{
		foreach($possibleOrderrowsClasses = static::getPossibleCustomQuotationrowsClasses() as $type)
		{
			$result = $type::query()->whereKey($id);

			if($relations)
				$result->with($relations);

			if($type == OperatorQuotationrow::gpc())
				$result = static::addOperatorOrderrowRelationships($result);

			if($row = $result->first())
				return $row;
		}

		return null;
	}

	static function getQuotationCompositeRowCollectionByIds(array|Collection $ids, array $relations = []) : Collection
	{
		$elements = collect();

		foreach($possibleOrderrowsClasses = static::getPossibleCustomQuotationrowsClasses() as $type)
		{
			{
				$result = $type::query()->whereIn('id', $ids);

				if($relations)
					$result->with($relations);

				if($type == OperatorQuotationrow::gpc())
				{
				}

				$elements = $elements->merge($result->get());
			}
		}

		return $elements;
	}

	static function getCustomSpecificRowById(int|string $id, array $relations = []) : ?Orderrow
	{
		foreach($possibleOrderrowsClasses = static::getPossibleCustomOrderrowsClasses() as $type)
		{
			$result = $type::query()->whereKey($id);

			if($relations)
				$result->with($relations);

			if($type == OperatorOrderrow::gpc())
				$result = static::addOperatorOrderrowRelationships($result);

			if($row = $result->first())
				return $row;
		}

		return null;
	}

	static function getCompositeOrderQuotationRowCollectionByIds(array|Collection $ids, array $relations = []) : Collection
	{
		return static::getCompositeRowCollectionByIds($ids, $relations)->merge(
			static::getQuotationCompositeRowCollectionByIds($ids, $relations)
		);
	}

	static function getCompositeRowCollectionByIds(array|Collection $ids, array $relations = []) : Collection
	{
		$elements = collect();

		foreach($possibleOrderrowsClasses = static::getPossibleCustomOrderrowsClasses() as $type)
		{
			$result = $type::query()->whereIn('id', $ids);

			if($relations)
				$result->with($relations);

			if($type == OperatorOrderrow::gpc())
				$result = static::addOperatorOrderrowRelationships($result);

			$elements = $elements->merge($result->get());
		}

		return $elements;
	}

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

	static function getSortingIndexByType(ProductPackageBaseRowcontainerModel $containerModel, string $type) : int
	{
		return ($containerModel->rows()->bySellableType($type)->max('sorting_index') ?? 0) + 1;
	}

	/**
	 * Come getSortingIndexByType ma sul campo type della riga:
	 * serve alle righe che non hanno ancora un sellable associato
	 **/
	static function getSortingIndexByRowType(ProductPackageBaseRowcontainerModel $containerModel, string $type) : int
	{
		return ($containerModel->rows()->where('type', $type)->max('sorting_index') ?? 0) + 1;
	}
}
