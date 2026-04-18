<?php

namespace IlBronza\Products\Models\Interfaces;

interface CustomRowInterface
{
	public function getTotalRowCostAttribute() : float;
	public function getTotalRowRevenueAttribute() : float;
}