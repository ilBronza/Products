<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;

use function class_basename;
use function lcfirst;

class CustomOrderrow extends Orderrow
{
	static function getClassname() : string
	{
		$actualClassname = lcfirst(class_basename(static::class));

		return config("products.models.customOrderrows.{$actualClassname}.class");
	}

	public function getAddRowButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddTypedRowButton($container, static::$typeName);

//		return $this->getAddTypedRowButton($container, static::$typeName);
	}

	public function getAddRowTableButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddTypedRowTableButton($container, static::$typeName);
//		return $this->getAddRowByTypeUrl('service', true);

//		return $this->getAddTypedRowTableButton($container, static::$typeName);
	}

}