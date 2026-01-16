<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\Model\CRUDTimeRangesTrait;
use IlBronza\Products\Models\Traits\Orderrow\TypedOrderrowTrait;
use IlBronza\Timings\Interfaces\TimelineInterface;
use Illuminate\Support\Collection;

use function class_basename;
use function get_class;
use function get_class_methods;
use function is_string;

class ProductPackageBaseRowModel extends ProductPackageBaseModel
{
	use CRUDTimeRangesTrait;
	use TypedOrderrowTrait;

	protected $casts = [
		'starts_at' => 'date',
		'ends_at' => 'date',
	];

	public function getFieldsToReset()
	{
		return [
		];
	}
	public function getModelContainerClass()
	{
		return get_class($this->modelContainer()->getRelated());
	}

	public function getFieldsToFreeze() : array
	{
		return [];
	}

	public function scopeByDate($query, Carbon $date)
	{
		return $query->where(function($_query) use($date)
		{
			$_query->where(function($__query) use($date)
			{
				$__query->where('starts_at', '<=', $date)->where('ends_at', '>=', $date);
			})->orWhere(function($__query) use($date)
			{
				$__query->whereNull('starts_at')->where('ends_at', '>=', $date);				
			})->orWhere(function($__query) use($date)
			{
				$__query->whereNull('ends_at')->where('starts_at', '<=', $date);				
			});
		})->orWhere(function($_query) use($date)
		{
			$_query->having('orders', function($__query) use($date)
			{
				$__query->byDate($date);
			});
		});
	}

	public function scopeBySellableSuppliers($query, Collection $sellableSuppliers)
	{
		if(! is_string($sellableSuppliers->first()))
			$sellableSuppliers = $sellableSuppliers->pluck('id');

		return $query->whereIn('sellable_supplier_id', $sellableSuppliers);
	}

	public function getHistoryUrlPlaceholder()
	{
		$pluralClass = $this->pluralLowerClass();
		$routeKey = $this->getCamelcaseClassBasename();

		return app('products')->route("{$pluralClass}.history", [$routeKey => config('datatables.replace_model_id_string')]);
	}

	static function boot()
	{
		parent::boot();

		static::deleting(function ($model)
		{
			if ($type = $model->getSellable()?->getType())
			{
				$container = $model->getModelContainer();

				$rows = $container->rows()->bySellableType($type)->orderBy('sorting_index')->get();

				foreach($rows as $index => $row)
					if(! $row->is($model))
					{
						$row->sorting_index = $index;
						$row->saveQuietly();
					}
			}
		});
	}

	public function getPossibleDriversArrayValues() : array
	{
		return $this->getPossibleOperatorsArrayValues();
	}

	public function getPossibleOperatorsArrayValues() : array
	{
		if (! $container = $this->getModelContainer())
			return [];

		return $container->getPossibleOperatorsArrayValues();
	}

	public function getPossiblePassengersArrayValues() : array
	{
		return $this->getPossibleOperatorsArrayValues();
	}

}