<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;

use function class_basename;
use function lcfirst;

class CustomOrderrow extends Orderrow
{
	public $routeBasename = 'ibProductsorderrows';
	public $routeClassname = 'orderrow';

    public function getForeignKey()
    {
        return 'orderrow_id';
    }

	static function getClassname() : string
	{
		$actualClassname = lcfirst(class_basename(static::class));

		return config("products.models.customOrderrows.{$actualClassname}.class");
	}

	public function getAddSellableSupplierButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddSellableSupplierButton($container, static::$typeName);
	}

	public function getAddRowButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddTypedRowButton($container, static::$typeName);
	}

	public function getAddRowTableButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddTypedRowTableButton($container, static::$typeName);
	}

	static public function getDesignedTargetConfigPackagePrefix() : string
	{
		return static::$designedTargetConfigPackagePrefix;
	}
}