<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Products\Models\Order as BaseOrder;
use IlBronza\Products\Models\Sellables\CommonSellableOrderQuotationTrait;

class Order extends BaseOrder
{
	use CommonSellableOrderQuotationTrait;

	public function getCalendarStatus() : ? string
	{
		$possibleStatus = [
			0 => 'In Corso',
			1 => 'Accettato',
			2 => 'Inviato',
			3 => 'Da modificare',
			4 => 'Rifiutato',
			5 => 'Sbagliato',
			6 => 'Eliminato',
			7 => 'Template',
		];

		return $possibleStatus[$this->state_id] ?? null;
	}

}