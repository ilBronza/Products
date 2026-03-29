<?php

namespace IlBronza\Products\Filament\Resources\ProductResource\Pages;

use Filament\Resources\Pages\ListRecords;
use IlBronza\Products\Filament\Resources\ProductResource;

class ListProducts extends ListRecords
{
	protected static string $resource = ProductResource::class;
}
