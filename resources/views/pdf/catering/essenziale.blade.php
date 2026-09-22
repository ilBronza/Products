<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $container->getName() ?? $documentTitleDefault }}</title>
	<style>
		@page { margin: 20px 24px; }
		body { color: #25332e; font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.45; }
		table { border-collapse: collapse; width: 100%; }
		.brand { color: #b06d4f; font-size: 8px; font-weight: bold; letter-spacing: 1.6px; text-transform: uppercase; }
		h1 { font-family: DejaVu Serif, serif; font-size: 25px; font-weight: normal; line-height: 1.1; margin: 6px 0 10px; }
		.subtitle { color: #65736d; font-size: 11px; }
		.meta { border-bottom: 1px solid #d9dfda; border-top: 1px solid #d9dfda; margin: 22px 0 25px; }
		.meta td { padding: 8px 4px; vertical-align: top; width: 33.33%; }
		.meta-label { color: #8a948f; display: block; font-size: 7px; letter-spacing: 1px; margin-bottom: 2px; text-transform: uppercase; }
		.section { margin-top: 20px; }
		.section h2 { color: #b06d4f; font-size: 9px; letter-spacing: 1.3px; margin: 0 0 7px; text-transform: uppercase; }
		.items th { border-bottom: 1px solid #cbd4ce; color: #78827d; font-size: 7px; letter-spacing: .8px; padding: 5px 3px; text-align: left; text-transform: uppercase; }
		.items td { border-bottom: 1px solid #e5eae6; padding: 7px 3px; vertical-align: top; }
		.items .quantity, .items .price { text-align: right; white-space: nowrap; }
		.item-name { font-size: 10px; }
		.item-supplier { color: #77827c; font-size: 8px; margin-top: 1px; }
		.total { background: #25332e; color: #fff; margin-top: 24px; padding: 13px 15px; }
		.total-label { font-size: 8px; letter-spacing: 1px; text-transform: uppercase; }
		.total-value { font-family: DejaVu Serif, serif; font-size: 20px; text-align: right; }
		.note { color: #6e7873; font-size: 8px; margin-top: 18px; }
	</style>
</head>
<body>
	@php
		$formatMoney = static function ($amount): string {
			return is_numeric($amount) ? 'EUR ' . number_format((float) $amount, 2, ',', '.') : '-';
		};

		$sections = [
			['title' => 'Menu e proposte', 'rows' => $productRows],
			['title' => 'Servizio e cura', 'rows' => $operatorRows],
			['title' => 'Logistica', 'rows' => $vehicleRows],
		];
	@endphp

	<div class="brand">Proposta catering</div>
	<h1>{{ $container->getName() ?? $documentTitleDefault }}</h1>
	<div class="subtitle">Una proposta costruita intorno al vostro momento speciale.</div>

	<table class="meta">
		<tr>
			<td>
				<span class="meta-label">Per</span>
				{{ $container->getClient()?->getName() ?? '-' }}
			</td>
			<td>
				<span class="meta-label">Quando</span>
				{{ $container->getStartsAt()?->format('d/m/Y') ?? '-' }}
			</td>
			<td>
				<span class="meta-label">Dove</span>
				{{ $container->getDestination()?->getName() ?? '-' }}
			</td>
		</tr>
	</table>

	@if($container->description ?? null)
		<p>{{ strip_tags($container->description) }}</p>
	@endif

	@foreach($sections as $section)
		@if($section['rows']->isNotEmpty())
			<section class="section">
				<h2>{{ $section['title'] }}</h2>
				<table class="items">
					<thead>
						<tr>
							<th>Dettaglio</th>
							<th class="quantity">Quantita</th>
							<th class="price">Importo</th>
						</tr>
					</thead>
					<tbody>
						@foreach($section['rows'] as $row)
							@php
								$lineTotal = method_exists($row, 'getCalculatedTotalRowRevenue')
									? $row->getCalculatedTotalRowRevenue()
									: ($row->total_price ?? $row->total_cost ?? null);
							@endphp
							<tr>
								<td>
									<div class="item-name">{{ $row->description ?: ($row->getSellableName() ?? '-') }}</div>
									@if($row->getSupplierName())
										<div class="item-supplier">{{ $row->getSupplierName() }}</div>
									@endif
								</td>
								<td class="quantity">{{ $row->getQuantity() ?? '-' }}</td>
								<td class="price">{{ $formatMoney($lineTotal) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</section>
		@endif
	@endforeach

	@if(is_numeric($container->getTotalRevenue()))
		<table class="total">
			<tr>
				<td class="total-label">Investimento complessivo</td>
				<td class="total-value">{{ $formatMoney($container->getTotalRevenue()) }}</td>
			</tr>
		</table>
	@endif

	<div class="note">Prezzi IVA esclusa salvo dove diversamente indicato. Grazie per averci scelto per questo momento.</div>

	@include('products::pdf._contractDocuments')
</body>
</html>
