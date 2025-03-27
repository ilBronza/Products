<?php

namespace IlBronza\Products\Models;

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