<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

trait TypedOrderrowTrait
{
	/**
	 * Default type name, can be overridden by subclasses.
	 *
	 * @var string|null
	 */
	protected static ?string $typeName = null;

	/**
	 * Automatically assigns a type value when creating a model.
	 */
	protected static function bootTypedOrderrowTrait()
	{
		static::creating(function ($model) {
			if (! $model->type && property_exists(static::class, 'typeName')) {
				$model->type = static::$typeName;
			}
		});

		static::addGlobalScope('typedOrderrow', function ($builder) {
			if (property_exists(static::class, 'typeName') && static::$typeName) {
				$builder->where('type', static::$typeName);
			}
		});
	}
}