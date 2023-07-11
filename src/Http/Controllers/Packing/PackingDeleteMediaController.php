<?php

namespace IlBronza\Products\Http\Controllers\Packing;

use IlBronza\CRUD\Models\Media;
use IlBronza\CRUD\Traits\CRUDDeleteMediaTrait;
use IlBronza\Products\Http\Controllers\Packing\PackingCRUD;

class PackingDeleteMediaController extends PackingCRUD
{
    use CRUDDeleteMediaTrait;

    public $allowedMethods = ['deleteMedia'];

    public function deleteMedia($packing, Media $media)
    {
        return $this->_deleteMedia($packing, $media);
    }
}
