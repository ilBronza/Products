<?php

use IlBronza\Products\Models\Order;
use IlBronza\Products\ResourceFilters\OrderFilters;
use IlBronza\Products\ResourceFilters\OrderFilterScope;

return [
    'model' => Order::class,
    'helper' => OrderFilters::class,
    'scope' => OrderFilterScope::class,
    'label' => 'products::resource-filters.orders',
    'enabled' => false,
];
