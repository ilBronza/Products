<?php

namespace IlBronza\Products\Models\Traits\Order;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDReorderableStandardTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Payments\Models\Traits\InteractsWithInvoiceables;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;

use function round;

trait CommonOrderrowQuotationrowTrait
{
	use CRUDParentingTrait;
	use InteractsWithPriceTrait;
	use InteractsWithFormTrait;
	use CRUDReorderableStandardTrait;
	use InteractsWithInvoiceables;

	use CommonOrderrowQuotationrowRelationAndScopesTrait;

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

	public function getAssignSellablesupplierUrl()
	{
		return $this->getKeyedRoute('assignSellableSupplier');
	}

	public function getFindOrAssociateSupplierUrl()
	{
		return $this->getKeyedRoute('findOrAssociateSupplier');
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

	public function getCalculatedKmAttribute()
	{
		if ($value = $this->km)
			return round($value, 2);

		return $this->getModelContainer()->getKm();
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

	public function getInvoiceableDetail() : string
	{
		if ($this->description)
			return $this->description;

		return $this->getSellable()->getName();
	}
}