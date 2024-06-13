<?php

namespace IlBronza\Products;

use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\CRUD\Providers\RouterProvider\RoutedObjectInterface;
use IlBronza\CRUD\Traits\IlBronzaPackages\IlBronzaPackagesTrait;

class Products implements RoutedObjectInterface
{
    use IlBronzaPackagesTrait;

    static $packageConfigPrefix = 'products';

    public function manageMenuButtons()
    {
        if(! $menu = app('menu'))
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
            'text' => 'products::projects.projects',
            'children' => [
                [
                    'icon' => 'list',
                    'href' => $this->route('projects.index'),
                    'text' => 'products::projects.index'
                ],
            ]
        ]);

        $servicesContainerButton = $menu->createButton([
            'name' => 'servicesContainer',
            'icon' => 'user-gear',
            'text' => 'products::services.services'
        ]);

        $suppliesContainerButton = $menu->createButton([
            'name' => 'suppliesContainer',
            'icon' => 'user-gear',
            'text' => 'products::supplies.supplies'
        ]);

        $suppliersButton = $menu->createButton([
            'name' => 'suppliers',
            'icon' => 'user-gear',
            'href' => $this->route('suppliers.index'),
            'text' => 'products::supplies.supplies'
        ]);

        $sellablesButton = $menu->createButton([
            'name' => 'sellables',
            'icon' => 'user-gear',
            'href' => $this->route('sellables.index'),
            'text' => 'products::sellables.supplies'
        ]);

        $suppliesContainerButton->addChild($suppliersButton);
        $suppliesContainerButton->addChild($sellablesButton);


        $productsGeneralManagerButton->addChild($projectsContainerButton);
        $productsGeneralManagerButton->addChild($productsContainerButton);
        $productsGeneralManagerButton->addChild($servicesContainerButton);
        $productsGeneralManagerButton->addChild($suppliesContainerButton);

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