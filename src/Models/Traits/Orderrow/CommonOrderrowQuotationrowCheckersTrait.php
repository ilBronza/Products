<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Order;

trait CommonOrderrowQuotationrowCheckersTrait
{
	public function isCostApproved() : ? bool
	{
		return $this->approved_total_row_cost;
	}

	public function isCostApprovedForTotals() : ? bool
	{
		return $this->isCostApproved();
	}

	public function isRevenueApproved() : ? bool
	{
		return $this->approved_total_row_revenue;
	}
}