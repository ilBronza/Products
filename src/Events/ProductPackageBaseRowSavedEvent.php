<?php

namespace IlBronza\Products\Events;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductPackageBaseRowSavedEvent implements ShouldDispatchAfterCommit
{
	use Dispatchable;
	use SerializesModels;

	public function __construct(
		public ProductPackageBaseRowModel $row
	) {}
}
