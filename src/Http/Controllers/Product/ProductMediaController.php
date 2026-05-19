<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Models\Media;
use IlBronza\CRUD\Traits\CRUDDeleteMediaTrait;
use Illuminate\Http\Request;

class ProductMediaController extends ProductCRUD
{
    use CRUDDeleteMediaTrait;

    public $allowedMethods = [
        'deleteMedia'
    ];

    public function deleteMedia($accessory, $media)
    {
        $media = Media::findOrFail($media);

        return $this->_deleteMedia($accessory, $media);
    }
}
