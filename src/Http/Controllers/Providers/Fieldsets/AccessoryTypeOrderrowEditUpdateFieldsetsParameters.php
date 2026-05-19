<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Products\Http\Controllers\Providers\Fieldsets\CateringOrderrowEditUpdateFieldsetsParameters;
use IlBronza\Products\Models\Sellables\Accessories\AccessoryType;

class AccessoryTypeOrderrowEditUpdateFieldsetsParameters extends CateringOrderrowEditUpdateFieldsetsParameters
{
	public function getModelForPriceFields()
	{
		return AccessoryType::gpc()::make();
	}
}
