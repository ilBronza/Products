<?php

namespace IlBronza\Products\Models\Sellables;

use App\Models\ProjectSpecific\Client;
use Carbon\Carbon;
use Exception;
use IlBronza\Buttons\Button;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Timeline\Traits\GanttTimelineTrait;
use IlBronza\CRUD\Models\BasePivotModel;

use IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Model\PackagedModelsTrait;
use IlBronza\Operators\Models\Interfaces\HasWorkingDays;
use IlBronza\Operators\Models\WorkingDay;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Prices\Providers\PriceData;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Traits\Sellable\SellableSupplierPricesTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use function app;
use function array_filter;
use function class_basename;
use function dd;
use function is_string;
use function request;
use function strpos;

class SellableSupplier extends BasePivotModel implements WithPriceInterface, HasWorkingDays, TimelineGroupInterface
{
	use CRUDUseUuidTrait;
	use PackagedModelsTrait;
	use GanttTimelineTrait;

	use CRUDModelExtraFieldsTrait;

	use InteractsWithPriceTrait;
	use SellableSupplierPricesTrait;
	// use UpdatePricesOnSaveTrait;

	static $packageConfigPrefix = 'products';

	static $deletingRelationships = ['prices', 'extraFields'];

	static $modelConfigPrefix = 'sellableSupplier';
	protected $keyType = 'string';

	static function getInternalIds() : array
	{
		dd('estendere lista fornitori interni');
	}

	public function scopeBySupplier($query, string|Supplier $supplier)
	{
		if(! is_string($supplier))
			$supplier = $supplier->getKey();

		return $query->where('supplier_id', $supplier);
	}

	public function workingDays()
	{
		return $this->hasMany(WorkingDay::gpc());
	}

	public function getPriceBaseAttributes()
	{
		return [
			'own_cost' => $this->getCost(),
			'sorting_index' => $this->getPriceSequence(),
		];
	}

	public function getCost()
	{
		// return $this->cardboard->getGifcoPrice();
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

	// public function getPriceExtraFieldsCasts() : array
	// {
	// 	$own = array_filter($this->getCasts(), function ($item)
	// 	{
	// 		if (strpos($item, 'CastFieldPrice') !== false)
	// 			return true;

	// 		return false;
	// 	});

	// 	if (! $sellable = $this->getSellable())
	// 		return $own;

	// 	$sellablePrices = $sellable->getPriceExtraFieldsCasts();

	// 	if (! $target = $sellable->getTarget())
	// 		return $own + $sellablePrices;

	// 	$targetPrices = $target->getPriceExtraFieldsCasts();

	// 	return $own + $sellablePrices + $targetPrices;
	// }

	public function getSellable() : ?Sellable
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

	public function getAllergensListString() : string
	{
		return cache()->remember(
			implode('_', [
				'sellable_supplier',
				$this->getKey(),
				'allergens_list_string',
				$this->getRawOriginal('updated_at'),
			]),
			3600,
			fn () : string => $this->getSellable()?->getTarget()?->allergens_list_string ?? ''
		);
	}

	public function getName() : string
	{
		return collect([
			$this->getSellableName(),
			$this->getSupplier()?->getName()
		])->filter()->implode(' - ') ?: (string) $this->getKey();
	}

	public function getTimelineGroupId() : string
	{
		return (string) $this->getKey();
	}

	public function getTimelineGroupName() : string
	{
		return $this->getName();
	}

	public function getTimelineGroupContent() : string
	{
		return $this->getName();
	}

	public function getTimelineGroupCssStyles() : array
	{
		return [];
	}

	public function getTimelineGroupHtmlClasses() : array
	{
		return [];
	}

	public function getTimelineGroupActions() : array
	{
		return [];
	}

	public function getTimelineGroupGanttUrl() : string
	{
		return $this->getGanttUrl();
	}

	public function getTimelineGroupModalUrl() : string
	{
		return 'sostituire con url buona';
	}

	public function getTimelineBindingDataArray() : array
	{
		dd('Dati dal sellableSupplier');
		return [

		];
	}

	public function sellable() : BelongsTo
	{
		return $this->belongsTo(
			config('products.models.sellable.class')
		)->withTrashed();
	}

	public function supplier() : BelongsTo
	{
		return $this->belongsTo(
			config('products.models.supplier.class'),
		)->withTrashed();
	}

	// public function setStandardPrices() : ?Collection
	// {
	// 	if (! $priceCreator = $this->getSellableTarget()->getPriceCreator())
	// 		return null;

	// 	dd('eliminare qua 22 aprile 2026. OCCHIO che sto coso viene chiamato in ogni caso quindi va eliminato tutto');

	// 	dd('secondo me sta roba va eliminata in favore del nuovo metodo');

	// 	$priceCreator->setModel($this);

	// 	return $priceCreator->createPrices();
	// }

	public function getSellableTarget() : SellableItemInterface
	{
		return $this->getSellable()->getTarget();
	}

	public function quotationrows()
	{
		return $this->hasMany(Quotationrow::gpc(), 'sellable_supplier_id');
	}

	public function orderrows()
	{
		return $this->hasMany(Orderrow::gpc(), 'sellable_supplier_id');
	}

	public function getAssignSellableSupplierToQuotationrowUrl()
	{
		return app('products')->route('quotationrows.associateSellableSupplier', [
			'quotationrow' => request()->quotationrow,
			'sellableSupplier' => $this->getKey()
		]);
	}

	public function getAssignBulkSellableSupplierToQuotationrowUrl()
	{
		return app('products')->route('quotationrows.associateBulkSellableSupplier', [
			'quotationrow' => request()->quotationrow,
			'sellableSupplier' => $this->getKey()
		]);
	}

	public function getAddSellableSupplierRowToQuotationUrl()
	{
		return app('products')->route('quotations.addSellableSupplierRow', [
			'quotation' => request()->quotation,
			'sellableSupplier' => $this->getKey()
		]);		
	}

	public function getAddSellableSupplierRowToOrderUrl()
	{
		return app('products')->route('orders.addSellableSupplierRow', [
			'order' => request()->order,
			'sellableSupplier' => $this->getKey()
		]);		
	}

	public function getAssignSellableSupplierToOrderrowUrl()
	{
		return app('products')->route('orderrows.associateSellableSupplier', [
			'orderrow' => request()->orderrow,
			'sellableSupplier' => $this->getKey()
		]);
	}

	public function getAssignBulkSellableSupplierToOrderrowUrl()
	{
		return app('products')->route('orderrows.associateBulkSellableSupplier', [
			'orderrow' => request()->orderrow,
			'sellableSupplier' => $this->getKey()
		]);
	}

	public function getCreateSellableSupplierButton($subject)
	{
		$supplier = $subject->getSupplier();

		return Button::create([
			'name' => 'sellable-supplier-create',
			'icon' => 'plus',
			'text' => 'products::sellableSuppliers.create',
			'href' => $supplier->getCreateSellableSupplierUrl(),
		]);
	}

	public function getCreateSellableButton(Client|Supplier|Sellable $subject) : Button
	{
		if (class_basename($subject) == 'Client')
			$subject = $subject->getSupplier();

		return Button::create([
			'name' => 'sellable-supplier-create',
			'icon' => 'plus',
			'text' => 'products::sellableSuppliers.create',
			'href' => $subject->getCreateSellableSupplierUrl(),
		]);
	}

	public function getSupplier() : ? Supplier
	{
		return $this->supplier;
	}

	public function getStoreBySupplierUrl()
	{
		$supplier = $this->getSupplier();

		return $supplier->getStoreSellableSupplierUrl();
	}

	public function getStoreBySellableUrl()
	{
		$sellable = $this->getSellable();

		return $sellable->getStoreSellableSupplierUrl();
	}

	public function mustAutomaticallyUpdatePrices() : bool
	{
		if(($bySellable = $this->getSellable()?->mustAutomaticallyUpdatePrices()) === false)
			return false;

		if(($bySupplier = $this->getSupplier()?->mustAutomaticallyUpdatePrices()) === false)
			return false;

		return $bySupplier || $bySellable;
	}

	static function getIdsBySellable(string|Sellable $sellable) : Collection
	{
		return static::query()->select('id')->where('sellable_id', is_string($sellable)? $sellable : $sellable->getKey())->pluck('id');
	}

	public function getBasePriceAttribute()
	{
		return 99887766;
	}

	public function getCachedPriceFieldsForSellable() : array
	{
		return $this->getSellable()?->getCachedPriceFieldsByType() ?? [];
	}

	public function getSellableClass() : string
	{
		return $this->sellable_class;
	}
}
