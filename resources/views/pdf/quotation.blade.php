<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $container->getName() ?? $documentTitleDefault }}</title>
	<style>
		body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
		table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
		th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
		th { background-color: #f0f0f0; font-weight: bold; }
		h1 { font-size: 18px; margin-bottom: 10px; }
		h2 { font-size: 14px; margin: 15px 0 8px 0; }
		.info-block { margin-bottom: 15px; }
		.text-right { text-align: right; }
	</style>
</head>
<body>
	<h1>{{ $container->getName() ?? $documentTitleDefault }}</h1>

	<div class="info-block">
		<strong>Cliente:</strong> {{ $container->getClient()?->getName() ?? '-' }}<br>
		<strong>Destinazione:</strong> {{ $container->getDestination()?->getName() ?? '-' }}<br>
		@if($container->getStartsAt())
			<strong>Periodo:</strong> {{ $container->getStartsAt()?->format('d/m/Y') }} - {{ $container->getEndsAt()?->format('d/m/Y') ?? '-' }}
		@endif
	</div>

	@if($operatorRows->isNotEmpty())
		<h2>@lang('products::models.operatorRows')</h2>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>@lang('products::fields.description')</th>
					<th>@lang('products::fields.quantity')</th>
					<th class="text-right">@lang('products::fields.price')</th>
					<th class="text-right">Totale</th>
				</tr>
			</thead>
			<tbody>
				@foreach($operatorRows as $index => $row)
					<tr>
						<td>{{ $index + 1 }}</td>
						<td>{{ $row->getSellableName() ?? '-' }} @if($row->getSupplierName()) - {{ $row->getSupplierName() }} @endif</td>
						<td>{{ $row->getQuantity() ?? '-' }}</td>
						<td class="text-right">{{ $row->price ?? '-' }}</td>
						<td class="text-right">{{ $row->total_price ?? $row->total_cost ?? '-' }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	@if($productRows->isNotEmpty())
		<h2>@lang('products::models.productOrderrows')</h2>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>@lang('products::fields.description')</th>
					<th>@lang('products::fields.quantity')</th>
					<th class="text-right">@lang('products::fields.price')</th>
					<th class="text-right">Totale</th>
				</tr>
			</thead>
			<tbody>
				@foreach($productRows as $index => $row)
					<tr>
						<td>{{ $index + 1 }}</td>
						<td>{{ $row->getSellableName() ?? '-' }} @if($row->getSupplierName()) - {{ $row->getSupplierName() }} @endif</td>
						<td>{{ $row->getQuantity() ?? '-' }}</td>
						<td class="text-right">{{ $row->price ?? '-' }}</td>
						<td class="text-right">{{ $row->total_price ?? $row->total_cost ?? '-' }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	@if($operatorRows->isEmpty() && $productRows->isEmpty())
		<p>Nessuna riga presente.</p>
	@endif
</body>
</html>
