<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\Products\Models\Interfaces\CustomRowInterface;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Traits\Customrow\CustomrowTrait;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;

abstract class CustomQuotationrow extends Quotationrow implements CustomRowInterface
{
	use CustomrowTrait;

	public $routeBasename = 'ibProductsquotationrows';
	public $routeClassname = 'quotationrow';
	static public $configModelClassname = 'customQuotationrow';

    public function getForeignKey()
    {
        return 'quotationrow_id';
    }

	public function getExtraFieldsClass(): ?string
	{
		return Orderrow::gpc()::make()->getExtraFieldsClass();
	}
}