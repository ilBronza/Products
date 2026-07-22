<?php

namespace IlBronza\Products\Http\Traits;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;

use function is_string;
use function response;

trait SellableRowAssignmentTrait
{
	public function addNewRowBySellable(ProductPackageBaseRowcontainerModel $container, Sellable|string $sellable)
	{
		if(is_string($sellable))
			$sellable = Sellable::gpc()::findOrFail($sellable);

		$helper = RowAssociatorHelper::associateRowBySellable($container, $sellable);

		return response()->json([
			'success' => true,
			'tablesToRefresh' => $helper->row->getTablesToRefresh(),
		]);
	}
}
