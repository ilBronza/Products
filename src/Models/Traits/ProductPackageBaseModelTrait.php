<?php

namespace IlBronza\Products\Models\Traits;

trait ProductPackageBaseModelTrait
{
	public function getRouteBaseNamePrefix() : ? string
	{
		return config('products.routePrefix');
	}

	static function getModelConfigPrefix()
	{
		return static::$modelConfigPrefix;
	}

	static function getProjectClassName()
	{
		return config('products.models.' . static::getModelConfigPrefix() . '.class');
	}

	public function getTable()
	{
		return config("products.models.{$this->getModelConfigPrefix()}.table");
	}

}