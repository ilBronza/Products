<?php

namespace IlBronza\Products\Models\Traits\Order;

use Carbon\Carbon;
use IlBronza\Addresses\Models\Address;
use IlBronza\Buttons\Button;
use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Clients\Models\Destination;
use IlBronza\Clients\Models\Traits\InteractsWithClientsTrait;
use IlBronza\Clients\Models\Traits\InteractsWithDestinationTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\Quotations\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait CommonOrderQuotationTrait
{
	use CRUDParentingTrait;

	use InteractsWithClientsTrait;
	use InteractsWithDestinationTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;
	use InteractsWithFormTrait;
	use InteractsWithPriceTrait;

	public function getDestination() : ?Destination
	{
		if (! $this->destination_id)
		{
			Log::critical('risolvere questa cosa con un destino default generico e non per cliente');

			return $this->getClient()?->getDefaultDestination();
		}

		if ($this->relationLoaded('destination'))
			return $this->destination;

		return $this->destination()->with('address')->first();
	}

	public function provideAddressModelForExtraFields() : ?Address
	{
		return $this->address;
	}

	public function project()
	{
		return $this->belongsTo(Project::gpc());
	}

	public function getProject() : ?Project
	{
		return $this->project;
	}

	public function scopeByClientId($query, string $clientId)
	{
		$query->where(static::gpc()::make()->getTable() . '.client_id', $clientId);
	}

	public function possibleProjects()
	{
		return $this->hasMany(
			Project::gpc(), 'client_id', 'client_id'
		);
	}

	public function possibleDestinations()
	{
		return $this->hasMany(
			Destination::gpc(), 'client_id', 'client_id'
		);
	}

	public function getPossibleProjectsValuesArray() : array
	{
		$possibleValues = $this->possibleProjects;

		return $possibleValues->pluck('name', 'id')->toArray();
	}

	public function getPossibleDestinationsValuesArray() : array
	{
		$possibleValues = $this->possibleDestinations;

		return $possibleValues->pluck('name', 'id')->toArray();
	}

	public function getChangeClientButton() : Button
	{
		return Button::create([
			'href' => $this->getChangeClientUrl(),
			'text' => 'products::orders.changeClient',
			'icon' => 'edit'
		]);
	}

	public function getResetRowsIndexesButton() : Button
	{
		return Button::create([
			'href' => $this->getResetRowsIndexesUrl(),
			'text' => 'products::orders.resetRowsIndex',
			'icon' => 'sort'
		]);
	}

	public function getChangeClientUrl() : string
	{
		return $this->getKeyedRoute('changeClientForm');
	}

	public function scopeByClientIds($query, array|Collection $clientsIds)
	{
		$query->whereIn('client_id', $clientsIds);
	}

	public function scopeByClientsIds($query, array|Collection $clientsIds)
	{
		$query->whereIn('client_id', $clientsIds);
	}

	public function getRows() : Collection
	{
		return $this->rows;
	}

	public function getCreateDestinationUrl()
	{
		return $this->getKeyedRoute('createDestination');
	}

	public function address()
	{
		return $this->hasOne(Address::gpc(), 'addressable_id', 'destination_id')->where('addressable_type', 'Destination');
	}

	public function provideDestinationModelForExtraFields() : ?Destination
	{
		return $this->destination;
	}

	public function scopeByEndingDateRange($query, $dateStart, $dateEnd)
	{
		return $query->whereHas('extrafields', function ($q) use ($dateStart, $dateEnd)
		{
			$q->whereBetween('ends_at', [$dateStart, $dateEnd]);
		});
	}

	public function scopeCurrent($query)
	{
		$query->whereHas('extrafields', function ($_query)
		{
			$_query->whereNull('ends_at');
			$_query->orWhere('ends_at', '>=', Carbon::now()->subMonths(6));
		});
	}

}