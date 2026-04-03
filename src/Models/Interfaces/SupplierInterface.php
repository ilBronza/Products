<?php

namespace IlBronza\Products\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface SupplierInterface
{
	/**
	 * ci dice se si devono aggiornare i prezzi ad ogni modifica
	 * 
	 * con Sellable:
	 * 	null fa la OR
	 * 	true o false fanno la AND
	 * 
	 **/

	public function mustAutomaticallyUpdatePrices() : ? bool;
}