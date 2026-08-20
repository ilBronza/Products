<?php

namespace IlBronza\Products\Models\Traits\Order;

use Carbon\Carbon;
use IlBronza\Buttons\Button;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDReorderableStandardTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Payments\Models\Traits\InteractsWithInvoiceables;
// use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Supplier;

use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsSellableSupplierAssociatorHelper;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

use function round;
use function route;

trait CommonOrderrowQuotationrowTrait
{
	use CRUDParentingTrait;
	// use InteractsWithPriceTrait;
	use InteractsWithFormTrait;
	use CRUDReorderableStandardTrait;
	use InteractsWithInvoiceables;

	use CommonOrderrowQuotationrowRelationAndScopesTrait;

	public function getOperatorAlerts()
	{
		return $this->getSellableSupplier()?->getSupplier()?->getTarget()?->getOperator()?->getModelAlertsAttribute($this->getModelContainer(), $this);
	}

	public function getBulkAssignManufacturerButton()
	{
		$button = Button::create([
			'href' => route("project.{$className}.{$routeName}", [$container]),
			'text' => 'buttons.' . $routeName,
			'icon' => 'print'
		]);

		ff($button);

		$button->setPrimary();
		//		$button->setHtmlClass('uk-margin-large-right');

		//		$button->setAjaxTableButton(null, [
		//			'openIframe' => true
		//		]);

		return $button;
	}

	public function setStartsAt(string|Carbon $startsAt = null) : void
	{
		$this->starts_at = $startsAt;
	}

	public function setEndsAt(string|Carbon $endsAt = null) : void
	{
		$this->ends_at = $endsAt;
	}

	public function getHasDifferentStartsAt()
	{
		if ($this->getStartsAt() != $this->getModelContainer()->getStartsAt())
			return 'differentstart';

		return null;
	}

	public function getStartsAt() : ?Carbon
	{
		return $this->starts_at;
	}

	public function getHasDifferentEndsAt()
	{
		if ($this->getEndsAt() != $this->getModelContainer()->getEndsAt())
			return 'differentend';

		return null;
	}

	public function getEndsAt() : ?Carbon
	{
		return $this->ends_at ?? $this->getModelContainer()?->getEndsAt();
	}

	public function getStartsAtAttribute($value)
	{
		if ($value)
			return Carbon::createFromFormat('Y-m-d H:i:s', $value);

		return $this->getModelContainer()?->getStartsAt();
	}

	public function getEndsAtAttribute($value)
	{
		if ($value)
			return Carbon::createFromFormat('Y-m-d H:i:s', $value);

		return $this->getModelContainer()?->getEndsAt();
	}

	public function getConcomitances() : Collection
	{
		if (! $supplier = $this->getSupplier())
			return $this->newCollection();

		$startsAt = $this->getStartsAt();
		$endsAt = $this->getEndsAt();
		$modelContainerRelationName = $this->getModelContainerRelationName();

		$query = static::whereIn(
			'sellable_supplier_id',
			$supplier->getSellableSuppliersIds()
		)->where($this->getKeyName(), '!=', $this->getKey());

		// Inizio effettivo della riga candidata: riga.starts_at ?? contenitore.starts_at.
		if ($endsAt)
			$query->where(function ($query) use ($endsAt, $modelContainerRelationName)
			{
				$query->where('starts_at', '<=', $endsAt)
					->orWhere(function ($query) use ($endsAt, $modelContainerRelationName)
					{
						$query->whereNull('starts_at')
							->whereHas($modelContainerRelationName, function ($query) use ($endsAt)
							{
								$query->where(function ($query) use ($endsAt)
								{
									$query->where('starts_at', '<=', $endsAt)
										->orWhereNull('starts_at');
								});
							});
					});
			});

		// Fine effettiva della riga candidata: riga.ends_at ?? contenitore.ends_at.
		if ($startsAt)
			$query->where(function ($query) use ($startsAt, $modelContainerRelationName)
			{
				$query->where('ends_at', '>=', $startsAt)
					->orWhere(function ($query) use ($startsAt, $modelContainerRelationName)
					{
						$query->whereNull('ends_at')
							->whereHas($modelContainerRelationName, function ($query) use ($startsAt)
							{
								$query->where(function ($query) use ($startsAt)
								{
									$query->where('ends_at', '>=', $startsAt)
										->orWhereNull('ends_at');
								});
							});
					});
			});

		return $query->with([
			$modelContainerRelationName => fn ($query) => $query->with('extraFields'),
		])->get();
	}

	public function getAssignSellablesupplierUrl()
	{
		return $this->getKeyedRoute('assignSellableSupplier');
	}

	public function getFindOrAssociateSupplierUrl()
	{
		return $this->getKeyedRoute('findOrAssociateSupplier');
	}

	public function setSupplier(null|string|Supplier $supplier)
	{
		if(is_null($supplier))
			return RowsSellableSupplierAssociatorHelper::emptySellableSupplier($this);

		if (is_string($supplier))
			$supplier = Supplier::gpc()::find($supplier);

		return RowsSellableSupplierAssociatorHelper::associateSellableSupplierToRowBySupplier($this, $supplier);
	}

	public function getFullname() : string
	{
		return $this->getSellable()?->getName() . ' ' . $this->getModelContainer()?->getName();
	}

	public function getName() : ?string
	{
		return $this->getSellable()?->getName();
	}

	public function setParameter(string $key, mixed $value = null)
	{
		$parameters = $this->getParameters();

		$parameters[$key] = $value;

		$this->parameters = $parameters;
		$this->save();
	}

	public function getParameters() : array
	{
		return $this->parameters ?? [];
	}

	public function getParameter(string $key, mixed $default = null) : mixed
	{
		$parameters = $this->getParameters();

		return $parameters[$key] ?? $default;
	}

	public function getQuantity() : ?float
	{
		return $this->quantity;
	}

	public function getCalculatedTollHtmlClass() : string
	{
		if ($value = $this->toll)
			return 'tollforced';

		return 'tollstandard';
	}

	public function getDescription(int $limit = null) : ? string
	{
		if($limit)
			return mb_strimwidth($this->description, 0, $limit, '...');

		return $this->description;
	}

	public function getInvoiceableDetail() : string
	{
		if ($this->description)
			return $this->description;

		return $this->getSellable()->getName();
	}


	/**
	 *
	 * START ADDING ROWS METHODS
	 *
	 */

	public function getAddTypedRowButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		throw new \Exception('qua eliminare questa in favore dell\'helper 22 aprile 2026');

		// Log::critical('usa RowsButtonsHelper::getAddTypedRowButton($container, static::$typeName);');

		// $urlGetter = "getAdd{$type}Url";

		// $button = Button::create([
		// 	'href' => $container->{$urlGetter}(),
		// 	'text' => "products::orders.add{$type}Row",
		// 	'icon' => 'plus'
		// ]);

		// $button->setSecondary();

		// $button->setAjaxTableButton(null, [
		// 	'openIframe' => true
		// ]);

		// return $button;
	}

	/**
	 *
	 * END ADDING ROWS METHODS
	 *
	 */

	public function getForcedPrice(string $priceField) : ? float
	{
		throw new \Exception('eliminare questa 22 aprile 2026');
		// $forced = "forced_{$priceField}";

		// return $this->$forced;
	}

	public function provideInheritedPrice(string $priceField)
	{
		throw new \Exception('eliminare questa 22 aprile 2026');
		// $inherited = "inherited_{$priceField}";

		// if($this->$inherited)
		// 	return $this->$inherited;

		// $price = null;

		// if($sellableSupplier = $this->getSellableSupplier())
		// 	$price = $sellableSupplier->$priceField;

		// elseif($sellable = $this->getSellable())
		// 	$price = $sellable->$priceField;

		// if($price)
		// {
		// 	$this->$inherited = $price;
		// 	$this->save();
		// }

		// return $price;

		// dd($price);
		// throw new \Exception('qua');
	}

	public function provideHierarchicalPrice(string $priceField) : ? float
	{
		throw new \Exception('eliminare questa 22 aprile 2026');
		// if ($value = $this->getForcedPrice($priceField))
		// 	return $value;

		// return $this->provideInheritedPrice($priceField);
		// 	return $value;

		// if($sellableSupplier = $this->getSellableSupplier())
		// 	return $sellableSupplier->client_price;

		// return $this->getSellable()->client_price;

	}
}
