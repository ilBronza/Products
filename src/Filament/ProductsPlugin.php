<?php

namespace IlBronza\Products\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use IlBronza\Products\Filament\Resources\OrderResource;
use IlBronza\Products\Filament\Resources\ProductResource;

class ProductsPlugin implements Plugin
{
	public function getId(): string
	{
		return 'products-server-side';
	}

	public function register(Panel $panel): void
	{
		$panel->resources([
			OrderResource::class,
			ProductResource::class,
		]);
	}

	public function boot(Panel $panel): void
	{
		//
	}
}
