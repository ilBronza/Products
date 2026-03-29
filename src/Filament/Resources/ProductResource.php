<?php

namespace IlBronza\Products\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use IlBronza\Products\Models\Product\Product;

class ProductResource extends Resource
{
	protected static ?string $model = Product::class;

	protected static ?string $navigationIcon = 'heroicon-o-cube';

	protected static ?string $navigationLabel = 'Products (Server-Side)';

	protected static ?string $modelLabel = 'Product';

	protected static ?string $pluralModelLabel = 'Products';

	protected static ?string $recordTitleAttribute = 'name';

	protected static ?string $slug = 'products-server-side';

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				Tables\Columns\TextColumn::make('id')
					->label('ID')
					->sortable()
					->searchable(),
				Tables\Columns\TextColumn::make('name')
					->label(__('products::fields.name'))
					->sortable()
					->searchable(),
				Tables\Columns\TextColumn::make('client.name')
					->label(__('products::fields.client'))
					->sortable()
					->searchable()
					->placeholder('-'),
				Tables\Columns\TextColumn::make('created_at')
					->label(__('products::fields.created_at'))
					->dateTime()
					->sortable(),
				Tables\Columns\TextColumn::make('product_relations_count')
					->label(__('products::fields.product_relations_count'))
					->sortable(),
				Tables\Columns\TextColumn::make('media_count')
					->label(__('products::fields.media_count'))
					->sortable(),
				Tables\Columns\TextColumn::make('orders_count')
					->label(__('products::fields.orders_count'))
					->sortable(),
				Tables\Columns\TextColumn::make('active_orders_count')
					->label(__('products::fields.active_orders_count'))
					->sortable(),
			])
			->defaultSort('created_at', 'desc')
			->filters([
				//
			])
			->actions([
				Tables\Actions\ViewAction::make()
					->url(fn (Product $record): string => app('products')->route('products.show', ['product' => $record->getKey()])),
			])
			->bulkActions([
				//
			]);
	}

	public static function getPages(): array
	{
		return [
			'index' => \IlBronza\Products\Filament\Resources\ProductResource\Pages\ListProducts::route('/'),
		];
	}

	public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
	{
		$modelClass = config('products.models.product.class', Product::class);
		$query = $modelClass::query();

		// Stesso query base di ProductIndexController::_getIndexElementsByScope()
		$query->withCount(['media', 'orders', 'activeOrders', 'productRelations']);
		$query->with(['client']);

		return $query;
	}
}
