<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Clients\Models\Destination;
use IlBronza\Clients\Models\Traits\InteractsWithClientsTrait;
use IlBronza\Clients\Models\Traits\InteractsWithDestinationTrait;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

use function strtolower;

class Quotation extends ProductPackageBaseModel
{
	static $modelConfigPrefix = 'quotation';

	use CRUDUseUuidTrait;
	protected $casts = [
		'date' => 'date'
	];

	use InteractsWithPriceTrait;

	use InteractsWithFormTrait;
	use ProductPackageBaseModelTrait;
	use CRUDParentingTrait;

	use InteractsWithNotesTrait;
	use InteractsWithClientsTrait;
	use InteractsWithDestinationTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;
	protected $keyType = 'string';
	protected $deletingRelationships = [];

	public function quotationrows()
	{
		return $this->hasMany(Quotationrow::getProjectClassName());
	}

	public function project()
	{
		return $this->belongsTo(Project::getProjectClassName());
	}

	public function possibleProjects()
	{
		return $this->hasMany(
			Project::getProjectClassName(), 'client_id', 'client_id'
		);
	}

	public function possibleDestinations()
	{
		return $this->hasMany(
			Destination::getProjectClassName(), 'client_id', 'client_id'
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

	public function getStoreQuotationrowUrl() : string
	{
		return $this->getKeyedRoute('storeQuotationrow', [
			'quotation' => $this->getKey(),
		]);
	}

	public function getAddQuotationrowByTypeUrl(string $type) : string
	{
		return $this->getKeyedRoute('addQuotationrow', [
			'quotation' => $this->getKey(),
			'type' => $type
		]);
	}

	public function getPossibleSellablesByType(string $type) : array
	{
		$types = $this->getQuotationrowsPossibleSellableTypes();

		$type = strtolower($type);

		return $types[$type]();
	}

}