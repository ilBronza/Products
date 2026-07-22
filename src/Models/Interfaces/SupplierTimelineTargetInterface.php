<?php

namespace IlBronza\Products\Models\Interfaces;

use IlBronza\Products\Models\Sellables\Supplier;

/**
 * Implementata dal model target di un Supplier quando la timeline
 * del fornitore richiede un controller dedicato.
 *
 * Ritornando null si ricade sulla timeline generica del package products.
 */
interface SupplierTimelineTargetInterface
{
	public function getSupplierTimelineContainerUrl(Supplier $supplier, ?string $option = null) : ?string;
}
