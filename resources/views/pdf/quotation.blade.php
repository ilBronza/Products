<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $quotation->getName() ?? 'Preventivo' }}</title>
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
	<h1>{{ $quotation->getName() ?? 'Preventivo' }}</h1>

	<div class="info-block">
		<strong>Cliente:</strong> {{ $quotation->getClient()?->getName() ?? '-' }}<br>
		<strong>Destinazione:</strong> {{ $quotation->getDestination()?->getName() ?? '-' }}<br>
		@if($quotation->getStartsAt())
			<strong>Periodo:</strong> {{ $quotation->getStartsAt()?->format('d/m/Y') }} - {{ $quotation->getEndsAt()?->format('d/m/Y') ?? '-' }}
		@endif
	</div>

	@if($operatorQuotationrows->isNotEmpty())
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
				@foreach($operatorQuotationrows as $index => $row)
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

	@if($productQuotationrows->isNotEmpty())
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
				@foreach($productQuotationrows as $index => $row)
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

	@if($operatorQuotationrows->isEmpty() && $productQuotationrows->isEmpty())
		<p>Nessuna riga presente.</p>
	@endif
</body>
</html>
