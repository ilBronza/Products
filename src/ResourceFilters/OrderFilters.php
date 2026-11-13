<?php

namespace IlBronza\Products\ResourceFilters;

use IlBronza\CRUD\ResourceFilters\ResourceFilterHelper;

class OrderFilters extends ResourceFilterHelper
{
    public function getFilters(): array
    {
        return [
            'ids' => $this->identifiers('products::resource-filters.orders'),
        ];
    }
}
