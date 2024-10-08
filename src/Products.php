<?php

namespace IlBronza\Products;

use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\CRUD\Providers\RouterProvider\RoutedObjectInterface;
use IlBronza\CRUD\Traits\IlBronzaPackages\IlBronzaPackagesTrait;

// use IlBronza\Category\Models\Categorizable;
use IlBronza\Category\Models\Category;
use IlBronza\Products\Models\Sellables\Supplier;

class Products implements RoutedObjectInterface
{
	use IlBronzaPackagesTrait;

	static $packageConfigPrefix = 'products';

	public function getSuppliersChildren() : array
	{
		// $categories = Category::getProjectClassName()::whereIn(
		//     'id',
		//     Categorizable::select('category_id')
		//         ->distinct()
		//         ->where('categorizable_type', 'Supplier')
		//         ->pluck('category_id')
		// )->get();

		$categories = Category::where('name', 'fornitore')->with('children')->first()?->children ?? [];

		$result = [];

		$result[] = [
			'name' => 'allSuppliers',
			'icon' => 'list',
			'text' => 'suppliers.all',
			'href' => $this->route('suppliers.index')
		];

		foreach ($categories as $category)
			$result[] = [
				'name' => 'suppliersbycategory' . $category->getKey(),
				'icon' => 'list',
				'translatedText' => trans('products::suppliers.byCategory', ['category' => $category->getName()]),
				'href' => $this->route('suppliers.byCategory', [$category])
			];

		return $result;
	}

	public function manageMenuButtons()
	{
		if (! $menu = app('menu'))
			return;

		$button = $menu->provideButton([
			'text' => 'generals.settings',
			'name' => 'settings',
			'icon' => 'gear',
			'roles' => ['administrator']
		]);

		$productsGeneralManagerButton = $menu->createButton([
			'name' => 'productsManager',
			'icon' => 'user-gear',
			'text' => 'products::products.productsManagement'
		]);

		$button->addChild($productsGeneralManagerButton);

		$productsContainerButton = $menu->createButton([
			'name' => 'productsContainer',
			'icon' => 'user-gear',
			'text' => 'products::products.products'
		]);

		$projectsContainerButton = $menu->createButton([
			'name' => 'projectsContainer',
			'icon' => 'user-gear',
			'text' => 'products::products.projects',
			'children' => [
				[
					'icon' => 'list',
					'href' => $this->route('projects.index'),
					'text' => 'products::projects.index'
				],
			]
		]);

		$quotationsContainerButton = $menu->createButton([
			'name' => 'quotationsContainer',
			'icon' => 'user-gear',
			'text' => 'products::quotations.quotations',
			'children' => [
				[
					'icon' => 'box-archive',
					'href' => $this->route('quotations.index'),
					'text' => 'products::generals.all'
				],
				[
					'icon' => 'list',
					'href' => $this->route('quotations.current'),
					'text' => 'products::generals.current'
				],
			]
		]);

		$servicesContainerButton = $menu->createButton([
			'name' => 'servicesContainer',
			'icon' => 'user-gear',
			'text' => 'products::services.services'
		]);

		$suppliersContainerButton = $menu->createButton([
			'name' => 'suppliersContainer',
			'icon' => 'user-gear',
			'text' => 'products::suppliers.suppliers'
		]);

		$suppliersButton = $menu->createButton([
			'name' => 'suppliers',
			'icon' => 'user-gear',
			'href' => $this->route('suppliers.index'),
			'text' => 'products::suppliers.suppliers',
			'children' => $this->getSuppliersChildren()
		]);

		$sellablesButton = $menu->createButton([
			'name' => 'sellables',
			'icon' => 'user-gear',
			'href' => $this->route('sellables.index'),
			'text' => 'products::sellables.index'
		]);

		$sellableSuppliersButton = $menu->createButton([
			'name' => 'sellableSuppliers',
			'icon' => 'user-gear',
			'href' => $this->route('sellableSuppliers.index'),
			'text' => 'products::sellableSuppliers.index'
		]);

		$suppliersContainerButton->addChild($suppliersButton);
		$suppliersContainerButton->addChild($sellablesButton);
		$suppliersContainerButton->addChild($sellableSuppliersButton);

		$productsGeneralManagerButton->addChild($quotationsContainerButton);
		$productsGeneralManagerButton->addChild($projectsContainerButton);
		$productsGeneralManagerButton->addChild($productsContainerButton);
		$productsGeneralManagerButton->addChild($servicesContainerButton);
		$productsGeneralManagerButton->addChild($suppliersContainerButton);

		$currentProductsButton = $menu->createButton([
			'name' => 'products.current',
			'icon' => 'users',
			'text' => 'products::products.current',
			'href' => IbRouter::route($this, 'products.current')
		]);

		$productsButton = $menu->createButton([
			'name' => 'products.index',
			'icon' => 'users',
			'text' => 'products::products.list',
			'href' => IbRouter::route($this, 'products.index')
		]);

		$productsContainerButton->addChild($currentProductsButton);
		$productsContainerButton->addChild($productsButton);
		$productsContainerButton->addChild(
			$menu->createButton([
				'name' => 'accessories.index',
				'icon' => 'users',
				'text' => 'products::accessories.list',
				'href' => IbRouter::route($this, 'accessories.index')
			])
		);
	}

	public function getSellablesModelsClasses() : array
	{
		$models = config('products.sellables.models');

		return array_column($models, 'class');
	}

	static function getRouteName(string $routeName)
	{
		return config('products.routePrefix') . $routeName;
	}

	public function route(string $routeName, array $parameters = [])
	{
		return IbRouter::route($this, $routeName, $parameters);
	}
}