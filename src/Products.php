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

        $productsManagerButton = $menu->createButton([
            'name' => 'productsManager',
            'icon' => 'user-gear',
            'text' => 'products::products.list'
        ]);

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

        $button->addChild($productsManagerButton);

        $productsManagerButton->addChild($currentProductsButton);
        $productsManagerButton->addChild($productsButton);
        $productsManagerButton->addChild(
            $menu->createButton([
                'name' => 'accessories.index',
                'icon' => 'users',
                'text' => 'products::accessories.list',
                'href' => IbRouter::route($this, 'accessories.index')
            ])
        );
    }

    static function getRouteName(string $routeName)
    {
        return config('products.routePrefix') . $routeName;
    }

    public function route(string $routeName, array $parameters)
    {
        return IbRouter::route($this, $routeName, $parameters);
    }
}