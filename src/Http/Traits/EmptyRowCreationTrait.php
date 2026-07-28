<?php

namespace IlBronza\Products\Http\Traits;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\EmptyRowCreatorHelper;

use function response;

trait EmptyRowCreationTrait
{
	public function addNewEmptyRowByType(ProductPackageBaseRowcontainerModel $container, string $type)
	{
		$helper = EmptyRowCreatorHelper::createEmptyRowByType($container, $type);

		return response()->json([
			'success' => true,
			'tablesToRefresh' => $helper->getRow()->getTablesToRefresh(),
		]);
	}
}
