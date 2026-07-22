<?php

namespace IlBronza\Products\Models\Traits\Customrow;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait;
use IlBronza\Products\Casts\StoredOrCalculatedExtraField;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Traits\Orderrow\ExclusiveDiscountFieldsTrait;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;

trait CustomrowTrait
{
	use CRUDModelExtraFieldsTrait;
	use ExclusiveDiscountFieldsTrait;

	public string $fieldsGroupParametersKey;

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

		$casts['pdf_quotation_show'] = ExtraField::class;
		$casts['pdf_quotation_show_price'] = ExtraField::class;
		$casts['pdf_quotation_show_quantity'] = ExtraField::class;

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

	public function getAddRowSelectButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddRowSelectButton($container, static::$typeName);
	}

	public function getAddSellableSupplierButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddSellableSupplierButton($container, static::$typeName);
	}

	public function getAddBySupplierButton(ProductPackageBaseRowcontainerModel $container)
	{
		return RowsButtonsHelper::getAddSupplierButton($container, static::$typeName);
	}

	static function getClassname() : string
	{
		$actualClassname = lcfirst(class_basename(static::class));
		$configModelClass = static::$configModelClassname;

		return cconfig("products.models.{$configModelClass}s.{$actualClassname}.class");
	}

	public function getCalculatedVatAttribute()
	{
		if($value = $this->extraFields->forced_vat)
			return $value;

		return 10;
	}

	public function getCalculatedVat()
	{
		return $this->calculated_vat;
	}

	public function getCalculatedVatCostAttribute()
	{
		return $this->calculated_total_row_revenue * $this->calculated_vat / 100;
	}

	public function setCalculatedVatAttribute($value)
	{
		$this->extraFields->forced_vat = $value;
	}

	public function getPdfDescription() : ? string
	{
		return $this->client_description;
	}

	public function getTablesToRefresh() : array
	{
		return cconfig('products.tablesToRefreshByType.' . $this->getType());
	}
}