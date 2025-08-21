<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\Model\CRUDTimeRangesTrait;
use Illuminate\Support\Collection;
use function get_class;
use function get_class_methods;
use function is_string;

class ProductPackageBaseRowModel extends ProductPackageBaseModel
{
	use CRUDTimeRangesTrait;

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
}