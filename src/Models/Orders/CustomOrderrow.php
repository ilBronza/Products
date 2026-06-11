<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\Products\Models\Interfaces\CustomRowInterface;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Traits\Customrow\CustomrowTrait;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;
use IlBronza\Products\Traits\CURSORImageTrait;
use function class_basename;
use function lcfirst;

abstract class CustomOrderrow extends Orderrow implements CustomRowInterface
{
	use CustomrowTrait;
	use CURSORImageTrait;

	public $routeBasename = 'ibProductsorderrows';
	public $routeClassname = 'orderrow';
	static public $configModelClassname = 'customOrderrow';

    public function getForeignKey()
    {
        return 'orderrow_id';
    }

	public function getExtraFieldsClass(): ?string
	{
		if (config('products.models.orderrow.extraFields.enabled', true))
			return cconfig('products.models.orderrow.extraFields.class');

		return null;
	}
}