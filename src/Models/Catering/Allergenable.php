<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\PackagedClassesTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Allergenable extends BasePivotModel
{
	use CRUDUseUuidTrait;
	use PackagedClassesTrait;

	static $modelConfigPrefix = 'allergenable';
	static $packageConfigPrefix = 'products';

	public $incrementing = false;
	protected $keyType = 'string';

	/**
	 * MorphToMany configures these values on its custom pivot instances.
	 *
	 * BasePivotModel supplies the soft-delete behaviour required by this pivot;
	 * the methods below retain MorphPivot's type constraint when a pivot is
	 * restored, updated, or deleted.
	 */
	protected $morphType;
	protected $morphClass;

	protected function setKeysForSaveQuery($query)
	{
		$query->where($this->morphType, $this->morphClass);

		return parent::setKeysForSaveQuery($query);
	}

	protected function setKeysForSelectQuery($query)
	{
		$query->where($this->morphType, $this->morphClass);

		return parent::setKeysForSelectQuery($query);
	}

	public function getMorphType()
	{
		return $this->morphType;
	}

	public function setMorphType($morphType)
	{
		$this->morphType = $morphType;

		return $this;
	}

	public function setMorphClass($morphClass)
	{
		$this->morphClass = $morphClass;

		return $this;
	}

	public function allergen() : BelongsTo
	{
		return $this->belongsTo(Allergen::gpc());
	}

	public function allergenable() : MorphTo
	{
		return $this->morphTo();
	}

	public function getTable() : string
	{
		return config('products.models.allergenable.table');
	}
}
