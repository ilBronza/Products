<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Category\Models\Category;
use IlBronza\FormField\Casts\JsonFieldCast;
use IlBronza\Products\Models\Order as IbOrder;
use Illuminate\Support\Collection;

class Order extends IbOrder
{
	protected $casts = [
		'phases' => JsonFieldCast::class,
		'people' => JsonFieldCast::class,
		'people_coefficient' => JsonFieldCast::class,

		'date' => 'date',
		'started_at' => 'date',
		'ended_at' => 'date',
		'starts_at' => 'date',
		'ends_at' => 'date',
		'cost_coefficient' => ExtraField::class,
		'total_proposal' => ExtraField::class,
		'state_id' => ExtraField::class,
	];

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
		{
			if($coefficientParameters['name'] == $coefficientName)
			{
				return $coefficientParameters['quantity'];
			}

		}

		return null;
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

	public function getAllergensList() : Collection
	{
		$result = collect();

		$productRows = $this->getProductRows();

		foreach($productRows as $productRow)
			$result = $result->merge($productRow->getSellable()?->getTarget()?->getAllergensList());

		return $result->unique();
	}
}