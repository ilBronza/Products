<?php

namespace IlBronza\Products\Models;

use App\Providers\SizeCalculators\SizeCalculator;
use Carbon\Carbon;
use IlBronza\Products\Models\Interfaces\SizeInterface;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

abstract class Size extends ProductPackageBaseModel implements SizeInterface
{
	protected $casts = [
		'calculated_at' => 'datetime'
	];

	static $modelConfigPrefix = 'size';

	abstract function scopeHavingBaseData($query);

	public function scopeToCalculate($query)
	{
		$query->whereNull('calculated_at');
	}

	public function hasBeenCalculated() : bool
	{
		return !! $this->getCalculatedAt();
	}

	public function getCalculatedAt() : ? Carbon
	{
		return $this->calculated_at;
	}

	public function getProduct() : Product
	{
		return $this->getSizeable();
	}

	public function sizeable()
	{
		return $this->morphTo();
	}

	public function getSizeable() : Model
	{
		return $this->sizeable;
	}

	public function calculate()
	{
		$calculator = SizeCalculator::createBySize($this);


		$calculator->calculate();
	}
}