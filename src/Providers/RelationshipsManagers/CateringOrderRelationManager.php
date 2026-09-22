<?php

namespace IlBronza\Products\Providers\RelationshipsManagers;

use IlBronza\Buttons\Button;
use IlBronza\CRUD\Providers\RelationshipsManager\RelationshipsManager;
use IlBronza\Notes\Http\Controllers\CrudNoteController;
use IlBronza\Products\Models\Catering\Allergen;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Catering\Product;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Providers\Helpers\QuotationOrder\RowscontainerRelationsManagerParametersHelper;
use function config;
use function trans;

class CateringOrderRelationManager Extends RelationshipsManager
{
	protected function getCreateProductButtons() : array
	{
		if (! is_a(Product::gpc(), SellableItemInterface::class, true))
			return [];

		$order = $this->getModel();

		if (! $order?->exists || $order->isFrozen() || ! $order->userCanUpdate() || ! Product::gpc()::userCanCreate())
			return [];

		$button = Button::create([
			'name' => 'createProduct',
			'text' => 'products::orders.createProduct',
			'icon' => 'plus',
			'href' => app('products')->route('orders.createProduct', [
				'order' => $order->getKey(),
			]),
		]);

		$button->setSecondary();
		$button->setAsIframe();
		$button->setAjaxTableButton();
		$button->setData('method', 'GET');

		return [$button];
	}

	public  function getAllRelationsParameters() : array
	{
		$result = [
			'edit' => [
				'relations' => [
					'productRows' => array_merge(
						RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'productRows'),
						[
							'buttons' => $this->getCreateProductButtons()
						]
					),

					'vehicleRows' => RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'vehicleRows'),

					'operatorRows' => RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'operatorRows'),

					'accessoryRows' => RowscontainerRelationsManagerParametersHelper::getStandardRowrelationParameters($this->getModel(), 'accessoryRows'),

					'allergens' => [
						'controller' => config('products.models.allergen.controllers.index'),
						'elementGetterMethod' => 'getAllergensList',
						'relationType' => 'HasMany',
						'relatedModelClass' => Allergen::gpc(),
						'relatedModel' => Allergen::gpc()::make(),
					],
				]
			]
		];

		if(! Order::gpc()::canHaveChildren())
		{
			unset($result['show']['relations']['parent']);
			unset($result['show']['relations']['children']);
		}

		return $result;
	}
}
