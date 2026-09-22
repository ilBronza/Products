<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait;
use IlBronza\Category\Models\Category;
use IlBronza\FormField\Casts\JsonFieldCast;
use Illuminate\Support\Collection;

trait CateringOrderQuotationTrait
{
	use CRUDModelExtraFieldsTrait;
	use InteractsWithCateringAllergensTrait;

	public function initializeCateringOrderQuotationTrait()
	{
		$this->mergeCasts([
			'phases' => JsonFieldCast::class,
			'people' => JsonFieldCast::class,
			'people_coefficient' => JsonFieldCast::class,
			'date' => 'date',
			'started_at' => 'date',
			'ended_at' => 'date',
			'starts_at' => 'datetime',
			'ends_at' => 'datetime',
		]);
	}

	public function getPossibleCategoriesValuesArray() : array
	{
		$category = Category::gpc()::where('name', config('products.models.order.baseCategoryName', 'Tipologia Commesse'))->first();

		return $category->getElementsFlatTree()->filter(function($item) use($category) {
			return $item->getKey() != $category->getKey();
		})->pluck('name', 'id')->toArray();
	}

	public function getEditRelationshipsManagerClass()
	{
		return config("products.models." . static::$modelConfigPrefix . ".relationshipsManagerClasses.showCatering");
	}

	public function getPossiblePhasesArrayValues() : array
	{
		return array_column($this->phases, 'name', 'name');
	}

	public function getPossiblePeopleCoefficientArrayValues() : array
	{
		return array_column($this->people_coefficient, 'name', 'name');
	}

	public function getQuantityByPeopleCoefficient(string $coefficientName) : ? float
	{
		foreach($this->people_coefficient as $coefficientParameters)
			if($coefficientParameters['name'] == $coefficientName)
				return $coefficientParameters['quantity'];

		return $this->getBaseQuantity();
	}

	public function getPhasesList() : Collection
	{
		return collect($this->phases);
	}

	public function getCategoryName() : string
	{
		if($category = $this->getCategory())
			return $category->getName();

		return 'Catering';
	}

	public function getBaseQuantity() : ? float
	{
		return $this->base_quantity;
	}

}
