<?php

namespace IlBronza\Products\Models\Traits\Customrow;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait;
use IlBronza\Products\Casts\StoredOrCalculatedExtraField;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;

trait CustomrowTrait
{
	public string $fieldsGroupParametersKey;
	use CRUDModelExtraFieldsTrait;

	abstract public function getSingleCostAttribute() : float;
	abstract public function getSingleRevenueAttribute() : float;
	abstract public function getTotalRowCostAttribute() : float;
	abstract public function getTotalRowRevenueAttribute() : float;

	public function setCustomrowCasts(array $prices)
	{
		$casts = [];

		foreach ($prices as $field => $measurementUnit)
		{
			$casts['stored_' . $field] = ExtraField::class;
			$casts['calculated_' . $field] = StoredOrCalculatedExtraField::class;
		}

		$casts['client_description'] = ExtraField::class;
		$casts['quantity_coefficient'] = ExtraField::class;

		$this->casts = array_merge($this->casts, $casts);
	}

	public function getFieldsGroupParametersKey() : string
	{
		if(! isset($this->fieldsGroupParametersKey))
			throw new \Exception('declare fieldsGroupParametersKey for ' . get_class($this));

		return $this->fieldsGroupParametersKey;
	}

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