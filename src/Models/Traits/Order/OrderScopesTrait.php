<?php

namespace IlBronza\Products\Models\Traits\Order;

use IlBronza\Clients\Models\Destination;

trait OrderScopesTrait
{
    public function scopeWithDestinationCity($query)
    {
        $destinationTable = Destination::getProjectClassName()::make()->getTable();
        $query->addSelect([
            'live_destination_city' => Destination::getProjectClassName()::join(
                'addresses', 'addresses.id', '=', $destinationTable . '.address_id'
                    )
                    ->select('city')
                    ->whereColumn($destinationTable . '.id', 'products__orders.destination_id')
                    ->take(1)
        ]);
    }

    public function scopeWithDestinationZone($query)
    {
        $destinationTable = Destination::getProjectClassName()::make()->getTable();
        $query->addSelect([
            'live_destination_zone' => Destination::getProjectClassName()::select('zone')
                    ->whereColumn($destinationTable . '.id', 'products__orders.destination_id')
                    ->take(1)
        ]);
    }
}