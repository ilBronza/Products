<?php

namespace IlBronza\Products\Models\Sellables;

use Carbon\Carbon;
use Exception;
use IlBronza\CRUD\Models\BasePivotModel;
use IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Prices\Providers\PriceData;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Quotations\Quotationrow;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

use function array_filter;
use function strpos;

class SellableSupplier extends BasePivotModel implements WithPriceInterface
{
	use CRUDUseUuidTrait;

	static $packageConfigPrefix = 'products';
	use PackagedModelsTrait;

	use CRUDModelExtraFieldsTrait;
	use InteractsWithPriceTrait;

	static $modelConfigPrefix = 'sellableSupplier';
	protected $keyType = 'string';

	public function getPriceBaseAttributes()
	{
		return [
			'own_cost' => $this->getCost(),
			'sequence' => $this->getPriceSequence(),
		];
	}

	public function getCost()
	{
		// return $this->cardboard->getGifcoPrice();
	}

	public function _calculatePriceData(PriceData $priceData) : PriceData
	{
		dd($priceData);
		$papers = $this->cardboard->getPapers();

		$manufacturerDiscountByWave = $this->getManufacturerDiscountByWave();

		$manufacturerDiscountsByPapers = $this->getManufacturerDiscountsByPapers();
		$manufacturerTotalDiscountByPapers = array_sum($manufacturerDiscountsByPapers);

		$squareMtDiscount = $manufacturerDiscountByWave + $manufacturerTotalDiscountByPapers;
		$manufacturerDiscount = $this->getManufacturerDiscount();

		$heavyPapersDiscount = $this->getHeavyPaperDiscountByPapers($papers);

		$squareMtGifcoPrice = $this->getCost();

		$priceData->price = $squareMtGifcoPrice - ($squareMtDiscount + $manufacturerDiscount + $heavyPapersDiscount);

		$priceData->data = [
			'manufacturerDiscount' => $manufacturerDiscount,
			'heavyPapersDiscount' => $heavyPapersDiscount,
			'manufacturerDiscountByWave' => $manufacturerDiscountByWave,
			'manufacturerDiscountsByPapers' => $manufacturerDiscountsByPapers,
			'manufacturerTotalDiscountByPapers' => $manufacturerTotalDiscountByPapers,
			'squareMtDiscount' => $squareMtDiscount,
			'squareMtGifcoPrice' => $squareMtGifcoPrice,
			'realPriceRule' => "{__('prices.fields' . $squareMtGifcoPrice)} - ({__('prices.fields' . $squareMtDiscount} + {__('prices.fields' . $manufacturerDiscount} + {__('prices.fields' . $heavyPapersDiscount})",
			'realPrice' => "{$squareMtGifcoPrice} - ({$squareMtDiscount} + {$manufacturerDiscount} + {$heavyPapersDiscount})"
		];

		return $priceData;
	}

	public function _manageCalculationErrors(Exception $e)
	{
		// Ukn::e('Problemi col calcolo del prezzo per ' . $this->cardboard->getKey() . ': ' . $e->getMessage());
	}

	public function getPriceValidityFrom() : ?Carbon
	{
		// if(! $manufacturerWave = $this->getManufacturerWave())
		//     return Carbon::now();

		// if(! $validFrom = $manufacturerWave->getPriceValidityFrom())
		//     return Carbon::now();

		// return $validFrom;
	}

	public function getPriceValidityTo() : ?Carbon
	{
		// if(! $manufacturerWave = $this->getManufacturerWave())
		//     return null;

		// return $manufacturerWave->getValidTo();
	}

	public function getExtraFieldsClass() : ?string
	{
		return null;
	}

	public function getPriceExtraFieldsCasts() : array
	{
		$own = array_filter($this->getCasts(), function ($item)
		{
			if (strpos($item, 'CastFieldPrice') !== false)
				return true;

			return false;
		});

		if (! $sellable = $this->getSellable())
			return $own;

		$sellablePrices = $sellable->getPriceExtraFieldsCasts();

		if (! $target = $sellable->getTarget())
			return $own + $sellablePrices;

		$targetPrices = $target->getPriceExtraFieldsCasts();

		return $own + $sellablePrices + $targetPrices;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function getDirectPriceString() : ?string
	{
		if (! $directPrice = $this->getDirectPrice())
			return null;

		return $directPrice->getName();
	}

	public function getPricedName()
	{
		if (! $directPrice = $this->getDirectPrice())
			return $this->getSellable()?->getName() ?? '-';

		return $directPrice->price . "/" . $directPrice->getMeasurementUnitId() . " - " . $this->getSellable()?->getName() ?? '-';
	}

	public function getSellableName() : ?string
	{
		return $this->getSellable()?->getName();
	}

	public function sellable() : BelongsTo
	{
		return $this->belongsTo(
			config('products.models.sellable.class')
		);
	}

	public function supplier() : BelongsTo
	{
		return $this->belongsTo(
			config('products.models.supplier.class'),
		);
	}

	public function getSupplier() : Supplier
	{
		return $this->supplier;
	}

	public function setStandardPrices() : ?Collection
	{
		if (! $priceCreator = $this->getSellableTarget()->getPriceCreator())
			return null;

		$priceCreator->setModel($this);

		return $priceCreator->createPrices();
	}

	public function getSellableTarget() : SellableItemInterface
	{
		return $this->getSellable()->getTarget();
	}

	public function quotationrows()
	{
		return $this->hasMany(Quotationrow::getProjectClassName(), 'sellable_supplier_id');
	}

	public function getAssignSellableSupplierUrl()
	{
		return app('products')->route('quotationrows.associateSellableSupplier', [
			'quotationrow' => request()->quotationrow,
			'sellableSupplier' => $this->getKey()
		]);
	}
}