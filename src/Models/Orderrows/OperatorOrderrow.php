<?php

namespace IlBronza\Products\Models\Orderrows;

use Carbon\Carbon;
use IlBronza\Operators\Helpers\WorkingDay\WorkingDayProviderHelper;
use IlBronza\Products\Models\Orders\CustomOrderrow;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

use function count;
use function dd;
use function implode;

class OperatorOrderrow extends CustomOrderrow
{
	protected static ?string $typeName = 'operator';
	public $routeBasename = 'ibProductsorderrows';
	public $routeClassname = 'orderrow';

	//quantity_on_total
	public function getQuantityOnTotalAttribute()
	{
		if (! $date = $this->getModelContainer()?->getEndsAt())
			return 0;

		$total = $date->diffInDays($this->getModelContainer()?->getStartsAt(), true) + 1;

		return "{$this->quantity}/{$total}";
	}

	public function getQuantityAttribute() : ?float
	{
		if (! $starts = $this->getStartsAt())
			return null;

		if (! $ends = $this->getEndsAt())
			return null;

		$ends->addDay();

		if($this->getSupplier() && ($this->getSupplier()->getTarget()))
		{
			$workingDays = WorkingDayProviderHelper::getByOperatorRangeRaw(
				$this->getSupplier()->getTarget(),
				$this->getStartsAt(),
				$this->getEndsAt(),
				'real'
			);

			if(count($workingDays) > 0)
				dd('gestire questi');
		}

//		foreach ($workingDays as $workingDay)
//			if ($workingDay['status'] == 'tr')
//				$days -= 0.25;
//			else if ($workingDay['status'] == 'off')
//				$days -= 0.25;
//			else if ($workingDay['status'] == 'st')
//				$days -= 0.5;

		return $starts->diffInDaysFiltered(function(Carbon $date)
		{
			return ! $date->isWeekend();
		}, $ends);
	}

	public function getClientOperatorPopupUrl()
	{
		$className = $this->getCamelcaseClassBasename();
		$classNames = $this->getPluralCamelcaseClassBasename();

		return app('products')->route("{$classNames}.clientOperatorPopup", [
			$className => config('datatables.replace_model_id_string'),
			'iframed' => true
		]);
	}

	public function getCalculatedCostCompanyTotalHtmlClass()
	{
		dd('mettere questa su padovanio e basta');
		if ($value = $this->cost_company_total)
			return 'costcompanytotalforced';

		return 'costcompanytotalcalculated';
	}
}