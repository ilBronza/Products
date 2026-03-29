<?php

namespace IlBronza\Products\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use IlBronza\Products\Models\Order;

class OrderResource extends Resource
{
	protected static ?string $model = Order::class;

	protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

	protected static ?string $navigationLabel = 'Orders (Server-Side)';

	protected static ?string $modelLabel = 'Order';

	protected static ?string $pluralModelLabel = 'Orders';

	protected static ?string $recordTitleAttribute = 'name';

	protected static ?string $slug = 'orders-server-side';

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
				Tables\Columns\TextColumn::make('project.name')
					->label(__('products::fields.project'))
					->sortable()
					->searchable()
					->placeholder('-'),
				Tables\Columns\TextColumn::make('date')
					->label(__('products::fields.date'))
					->date()
					->sortable(),
				Tables\Columns\TextColumn::make('created_at')
					->label(__('products::fields.created_at'))
					->dateTime()
					->sortable(),
				Tables\Columns\TextColumn::make('starts_at')
					->label(__('products::fields.starts_at'))
					->dateTime()
					->sortable(),
				Tables\Columns\TextColumn::make('ends_at')
					->label(__('products::fields.ends_at'))
					->dateTime()
					->sortable(),
			])
			->defaultSort('created_at', 'desc')
			->filters([
				//
			])
			->actions([
				Tables\Actions\ViewAction::make()
					->url(fn (Order $record): string => app('products')->route('orders.show', ['order' => $record->getKey()])),
			])
			->bulkActions([
				//
			]);
	}

	public static function getPages(): array
	{
		return [
			'index' => \IlBronza\Products\Filament\Resources\OrderResource\Pages\ListOrders::route('/'),
		];
	}

	public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
	{
		$modelClass = config('products.models.order.class', Order::class);
		$query = $modelClass::query();

		// Stesso query base di OrderIndexController::getIndexElements()
		$query->with(['project', 'destination', 'parent', 'client', 'quotation']);

		if (method_exists($placeholder = $modelClass::make(), 'getExtraFieldsClass')) {
			if ($placeholder->getExtraFieldsClass()) {
				$query->with('extraFields');
			}
		}

		return $query;
	}
}
