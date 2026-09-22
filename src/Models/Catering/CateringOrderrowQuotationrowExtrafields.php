<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;

/**
 * Base extra-fields model shared by order rows and quotation rows in
 * catering projects.
 *
 * Applications can extend this class to keep their own table name and row
 * relationship while sharing the catering-specific base model.
 */
class CateringOrderrowQuotationrowExtrafields extends BaseModel
{
	use CRUDUseUuidTrait;

	protected $keyType = 'string';
}
