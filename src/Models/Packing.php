<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Media\InteractsWithMedia;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Products\Models\Interfaces\SizeInterface;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;

class Packing extends BaseModel implements SizeInterface, HasMedia
{
    use InteractsWithMedia;
	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
	protected $keyType = 'string';

	static $modelConfigPrefix = 'packing';

	protected $dates = [
		'imported_at',
		'calculated_at',
		'verified_at'
	];

	public function packable() : MorphTo
	{
		return $this->morphTo('packable');
	}
}