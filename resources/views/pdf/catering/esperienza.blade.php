<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $container->getName() ?? $documentTitleDefault }}</title>
	<style>
		@page { margin: 0; }
		body { color: #332a26; font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.5; margin: 0; }
		table { border-collapse: collapse; width: 100%; }
		.cover { background: #eadfce; min-height: 180px; padding: 42px 42px 32px; }
		.eyebrow { color: #966c48; font-size: 8px; font-weight: bold; letter-spacing: 1.8px; text-transform: uppercase; }
		h1 { font-family: DejaVu Serif, serif; font-size: 32px; font-weight: normal; line-height: 1.08; margin: 12px 0; }
		.lead { font-family: DejaVu Serif, serif; font-size: 14px; line-height: 1.45; max-width: 400px; }
		.content { padding: 30px 42px 36px; }
		.event-card { background: #f7f3ee; margin-bottom: 28px; padding: 13px 15px; }
		.event-card td { vertical-align: top; width: 33.33%; }
		.event-label { color: #96775c; display: block; font-size: 7px; letter-spacing: 1px; margin-bottom: 3px; text-transform: uppercase; }
		h2 { color: #966c48; font-size: 9px; letter-spacing: 1.5px; margin: 23px 0 8px; text-transform: uppercase; }
		.story { color: #604f45; font-family: DejaVu Serif, serif; font-size: 13px; line-height: 1.55; margin: 0 0 13px; }
		.list td { border-bottom: 1px solid #e5ddd4; padding: 8px 0; vertical-align: top; }
		.list .quantity { color: #8a7462; text-align: right; white-space: nowrap; width: 50px; }
		.list-name { font-size: 10px; }
		.list-detail { color: #887b72; font-size: 8px; margin-top: 2px; }
		.investment { background: #332a26; color: #fff; margin-top: 28px; padding: 17px 18px; }
		.investment-label { font-size: 8px; letter-spacing: 1px; text-transform: uppercase; }
		.investment-value { font-family: DejaVu Serif, serif; font-size: 23px; text-align: right; }
		.closing { color: #604f45; font-family: DejaVu Serif, serif; font-size: 13px; line-height: 1.55; margin-top: 28px; }
		.legal { color: #887b72; font-size: 7px; margin-top: 18px; }
	</style>
</head>
<body>
	@php
		$formatMoney = static function ($amount): string {
			return is_numeric($amount) ? 'EUR ' . number_format((float) $amount, 2, ',', '.') : '-';
		};

		$sections = [
			['title' => 'Il menu', 'intro' => 'Sapori pensati per accogliere, sorprendere e far sentire ogni ospite al posto giusto.', 'rows' => $productRows],
			['title' => 'La cura del momento', 'intro' => "Persone, servizio e dettagli coordinati per lasciare spazio alla serenita di vivere l'evento.", 'rows' => $operatorRows],
			['title' => 'Tutto al suo posto', 'intro' => "La logistica necessaria per trasformare l'idea in un'esperienza senza pensieri.", 'rows' => $vehicleRows],
		];
	@endphp

	<div class="cover">
		<div class="eyebrow">Catering su misura</div>
		<h1>{{ $container->getName() ?? $documentTitleDefault }}</h1>
		<div class="lead">Un invito a stare bene insieme, costruito con attenzione per le persone che lo vivranno.</div>
	</div>

	<div class="content">
		<table class="event-card">
			<tr>
				<td>
					<span class="event-label">Per</span>
					{{ $container->getClient()?->getName() ?? '-' }}
				</td>
				<td>
					<span class="event-label">Quando</span>
					{{ $container->getStartsAt()?->format('d/m/Y') ?? '-' }}
				</td>
				<td>
					<span class="event-label">Dove</span>
					{{ $container->getDestination()?->getName() ?? '-' }}
				</td>
			</tr>
		</table>

		@if($container->description ?? null)
			<p class="story">{{ strip_tags($container->description) }}</p>
		@endif

		@foreach($sections as $section)
			@if($section['rows']->isNotEmpty())
				<section>
					<h2>{{ $section['title'] }}</h2>
					<p class="story">{{ $section['intro'] }}</p>
					<table class="list">
						<tbody>
							@foreach($section['rows'] as $row)
								<tr>
									<td>
										<div class="list-name">{{ $row->description ?: ($row->getSellableName() ?? '-') }}</div>
										@if($row->getSupplierName())
											<div class="list-detail">{{ $row->getSupplierName() }}</div>
										@endif
									</td>
									<td class="quantity">{{ $row->getQuantity() ?? '' }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</section>
			@endif
		@endforeach

		@if(is_numeric($container->getTotalRevenue()))
			<table class="investment">
				<tr>
					<td class="investment-label">Investimento complessivo</td>
					<td class="investment-value">{{ $formatMoney($container->getTotalRevenue()) }}</td>
				</tr>
			</table>
		@endif

		<p class="closing">Ogni proposta prende forma nei dettagli. Saremo felici di costruire insieme il vostro.</p>
		<div class="legal">Prezzi IVA esclusa salvo dove diversamente indicato.</div>
	</div>

	@include('products::pdf._contractDocuments')
</body>
</html>
