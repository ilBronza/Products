<?php

namespace IlBronza\Products\Models\Traits\Customrow;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;

trait CustomrowTrait
{
	public function getAddRowTableButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddTypedRowTableButton($container, static::$typeName);
	}

	static public function getDesignedTargetConfigPackagePrefix() : string
	{
		return static::$designedTargetConfigPackagePrefix;
	}

	public function getAddRowButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddTypedRowButton($container, static::$typeName);
	}

	public function getAddSellableSupplierButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddSellableSupplierButton($container, static::$typeName);
	}

	static function getClassname() : string
	{
		$actualClassname = lcfirst(class_basename(static::class));
		$configModelClass = static::$configModelClassname;

		return config("products.models.{$configModelClass}s.{$actualClassname}.class");
	}
}