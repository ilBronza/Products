<?php

namespace IlBronza\Products\Http\Controllers\Accessory;

use IlBronza\CRUD\Models\Media;
use IlBronza\CRUD\Traits\CRUDDeleteMediaTrait;

class AccessoryMediaController extends AccessoryCRUD
{
	use CRUDDeleteMediaTrait;

	public $allowedMethods = [
		'deleteMedia'
	];

	public function deleteMedia($accessory, Media $media)
	{
		return $this->_deleteMedia($accessory, $media);
	}

}
