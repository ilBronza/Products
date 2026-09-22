<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\ProductPackageBaseRowModel;

class RowSortingIndexesOnDeletingHelper
{
	public static function recalculate(ProductPackageBaseRowModel $model) : void
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
	}
}
