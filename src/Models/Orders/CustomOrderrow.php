<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\Products\Models\Interfaces\CustomRowInterface;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Traits\Customrow\CustomrowTrait;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;
use function class_basename;
use function lcfirst;

class CustomOrderrow extends Orderrow implements CustomRowInterface
{
	public $routeBasename = 'ibProductsorderrows';
	public $routeClassname = 'orderrow';
	static public $configModelClassname = 'customOrderrow';

	use CustomrowTrait;

    public function getForeignKey()
    {
        return 'orderrow_id';
    }
}