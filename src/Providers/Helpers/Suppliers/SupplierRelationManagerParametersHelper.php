<?php

namespace IlBronza\Products\Providers\Helpers\Suppliers;

use IlBronza\Products\Models\Interfaces\SupplierInterface;

class SupplierRelationManagerParametersHelper
{
	static function getSupplierRelationManagerParameters(SupplierInterface $model) : array
	{
		return [
			'sellableSuppliers' => $model->getSellableSuppliersBySupplierRelationsManagerParameters(),
			'orderrows' => $model->getOrderrowsBySupplierRelationsManagerParameters(),
			'quotationrows' => $model->getQuotationrowsBySupplierRelationsManagerParameters()
		];
	}
}