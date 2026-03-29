<?php

namespace IlBronza\Products\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Pages\ListRecords;
use IlBronza\Products\Filament\Resources\OrderResource;

class ListOrders extends ListRecords
{
	protected static string $resource = OrderResource::class;
}
