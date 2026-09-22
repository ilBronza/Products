<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $container->getName() ?? $documentTitleDefault }}</title>
	<style>
		/* Margini foglio PDF (supportato da Dompdf tramite @page) */
		@page {
			margin: 20px 10px;
		}

		body
		{ font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.2; margin: 0; padding: 0; }
		table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
		th, td
		{
			padding: 2px 6px;
			text-align: left;
			vertical-align: top;
		}

		th
		{
			font-weight: bold;
		}

		/* PDF pagination (dompdf): try hard to not split table rows across pages */
		thead { display: table-header-group; }
		tfoot { display: table-footer-group; }
		table { page-break-inside: auto; }
		tr { page-break-inside: avoid !important; page-break-after: auto; }
		td, th { page-break-inside: avoid !important; }
		.doc-table tr, .doc-table td, .doc-table th { page-break-inside: avoid !important; }

		/* Keep phase header with at least the next row when possible */
		.tr-phase-title {
		 	page-break-inside: avoid !important;
		 	page-break-after: avoid !important;
			border-bottom:1px solid #ccc;
			padding:5px 6px 4px 6px;
			font-weight:bold;
			background:#fafafa;		 	
		}

		.border-top
		{
			border-top-width: 1px solid #ddd !important;
		}

		.border-top-dark
		{
			border-top :1px solid #333!important;
		}

		body table.doc-table tr.table-title
		{
			border-top :1px solid #333!important;
			border-bottom :1px solid #333!important;
			border-left :1px solid #333!important;
			border-right :1px solid #333!important;
		}

		body table.doc-table tr.table-title td:last-of-type()
		{
			border-right :1px solid #333!important;			
		}

		body table.doc-table tr.table-title td:first-of-type()
		{
			border-left :1px solid #333!important;			
		}

		.header-table { border: none; margin-bottom: 12px; }
		.header-table td, .header-table th { border: none; padding: 1px 8px 1px 0; }
		.doc-table thead th { font-size: 8px; border-bottom: 1px solid #333 !important; }
		/* No horizontal borders in body (keep only vertical ones) */
		.doc-table tbody td { border-top: none; border-bottom: none; }
		/* Vertical borders for all document tables */
		.doc-table th, .doc-table td { border-left: 1px solid #ddd; border-right: 1px solid #ddd; }
		.doc-table thead th { border-left-color: #333; border-right-color: #333; }
		.doc-table { border-left: 1px solid #ddd; border-right: 1px solid #ddd; }


		.col-img
		{
			width: 115px;
 			vertical-align:top;
 		}

		.thumb-frame
		{
			width: 110px;
			height: 110px;
			overflow: hidden;
		}
		
		.thumb-frame img
		{
			display: block;
		}

		.total-vatted
		{
			width: 65px;
			text-align: right;
			padding-left: 0px;
		}

		.vat-percent
		{
			width: 50px;
			text-align: right;
		}

		.single-cost
		{
			width: 45px;
			text-align: right;
		}

		.total-cost
		{
			width: 60px;
			text-align: right;
		}

		.quantity
		{
			width: 2px;
			text-align: center;
		}

		.code
		{
			width: 35px;
			text-align: right;
		}

		.product-price
		{
			text-align: right;			
		}

		table.container-details th
		{
			width: 60px;
			font-weight: bold;
		}

		table.pagamento
		{
			border: 1px solid #333;
			margin-top: 35px;
			width: 330px;
		}

		table.pagamento .banca
		{
			width: 200px;
			border-right: 1px solid #ccc;
		}

		table.pagamento tr + tr
		{
			border-top: 1px solid #ccc;
		}


		tr.total
		{
			font-weight: bold;
			border: 1px solid #333!important;
		}

		tr.total td:first-of-type
		{
			border-left: 1px solid #333!important;			
		}

		tr.total td:last-of-type
		{
			border-right: 1px solid #333!important;			
		}

		.company-header { font-size: 10px; margin-bottom: 15px; }
		.company-header .company-name { font-weight: bold; font-size: 11px; }

		.doc-title
		{
			font-size: 14px;
			font-weight: bold;
			margin: 10px 0 0 0;
		}

		.doc-date
		{
			margin-bottom: 15px;
		}

		.destinatario { font-weight: bold; margin-bottom: 5px; }

		.footer-note { font-size: 7px; margin-top: 15px; color: #555; }
		.totals-section { margin-top: 15px; font-size: 10px; }
	</style>
</head>
<body>
	@php
		$company = config('pdf.company', []);
		$documentType = $documentTitleDefault;
		$docNumber = $container->getName() ?? '-';
		$clientName = $container->getClient()?->getName() ?? '-';
		$destination = $container->getDestination();
		$destinationName = $destination?->getName() ?? '-';
		$destinationAddress = $destination?->address ? trim(implode(', ', array_filter([$destination->address->street ?? null, $destination->address->number ?? null, $destination->address->zip ?? null, $destination->address->town ?? null, $destination->address->city ?? null]))) : '';
		$extraFields = $container->extraFields ?? null;

		$pax = $extraFields->pax ?? null;

		/** Formatta importi in euro: migliaia con punto, decimali con virgola, sempre 2 cifre. */
		$formatEuro = static function ($value): string {
			if ($value === null || $value === '') {
				return '';
			}
			if (! is_numeric($value)) {
				return '';
			}
			return number_format((float) $value, 2, ',', '.') . ' €';
		};

	@endphp

	<div class="company-header">
		<div class="company-name">{{ $company['name'] ?? config('clients.ownCompanyName', '') }}</div>
		<div>{{ $company['address'] ?? '' }}</div>
		<div>Tel. {{ $company['phone'] ?? '' }}  @if (!empty($company['fax'])) Fax {{ $company['fax'] }} @endif</div>
		<div>e-mail: {{ $company['email'] ?? '' }}  @if (!empty($company['pec'])) Pec: {{ $company['pec'] }} @endif  @if (!empty($company['website'])) Internet: {{ $company['website'] }} @endif</div>
		<div>C.F. {{ $company['cf'] ?? '' }} P.Iva {{ $company['piva'] ?? '' }}</div>
	</div>

	<table class="header-table" style="width: 100%;">
		<tr>
			<td style="width: 50%;">
				<table class="container-details">
					<tr>
						<td class="doc-title" colspan="2">
							Preventivo {{ $docNumber }} del {{ $container->created_at?->format('d/m/Y') ?? '-' }}
						</td>
					</tr>
					<tr>
						<th>Cliente:</th>
						<td>{{ $clientName }}</td>
					</tr>
					<tr>
						<th>Evento:</th>
						<td>{{ $container->starts_at?->format('d/m/Y') }}</td>
					</tr>

				@if($container->description)
					<tr>
						<th>Note:</th>
						<td>{!! $container->description !!}</td>
					</tr>
				@endif

					<tr>
						<th>Pax:</th>
						<td>
							<table>
								@foreach($container->getPeopleCoefficientDescriptionArray() as $peopleName => $peopleQuantity)
								<tr>
									<td style="width: 50px;">{{ ucfirst($peopleName) }}</td>
									<td>{{ $peopleQuantity }}</td>
								</tr>
								@endforeach
							</table>
						</td>
					</tr>

				</table>

			</td>
			<td style="width: 50%; text-align: right; vertical-align: top;">
				@if($destination = $container->getDestination())
				<div><strong>Destinazione</strong></div>
				<div>{{ $destination->getName() }}</div>
				<div>{!! $destination->getFormattedFullString() !!}</div>
				@endif
			</td>
		</tr>
	</table>

	@php
		$groupedProductRows = $container->getGroupedRowsByTypeField('productRows', 'people_coefficient');
		$phaseGroupedProductRows = $container->getGroupedRowsByTypeField('productRows', 'phase');

		$allProductRows = $groupedProductRows->flatten(1)->map(fn($r) => (object) ['row' => $r, 'type' => 'product']);

		$rawPhases = $container->phases ?? null;
		if (is_string($rawPhases)) {
			$rawPhasesTrimmed = trim($rawPhases);
			if ($rawPhasesTrimmed !== '' && !str_starts_with($rawPhasesTrimmed, '[')) {
				$rawPhasesTrimmed = '[' . $rawPhasesTrimmed . ']';
			}
			$decodedPhases = json_decode($rawPhasesTrimmed, true);
		} elseif (is_array($rawPhases)) {
			$decodedPhases = $rawPhases;
		} else {
			$decodedPhases = null;
		}

		$phases = collect(is_array($decodedPhases) ? $decodedPhases : [])
			->filter(fn($p) => is_array($p) && !empty($p['name']) && !empty($p['starts_at']))
			->sortBy(fn($p) => (string) $p['starts_at'])
			->values();

		$phaseIndexByName = $phases
			->values()
			->mapWithKeys(fn($p, $i) => [(string) $p['name'] => $i])
			->all();

		$phaseMetaByName = $phases
			->mapWithKeys(fn($p) => [(string) $p['name'] => $p])
			->all();

		$extractPhaseName = function ($row): ?string {
			$phase = $row->phase ?? null;
			if (is_string($phase)) {
				$phase = trim($phase);
				return $phase !== '' ? $phase : null;
			}
			if (is_object($phase) && isset($phase->name) && is_string($phase->name)) {
				$phase = trim($phase->name);
				return $phase !== '' ? $phase : null;
			}
			return null;
		};

		$rowsTotalRevenue = $allProductRows
			->map(fn($x) => $x->row?->getCalculatedTotalRowRevenue())
			->filter(fn($v) => is_numeric($v))
			->sum();

		$coeffTotals = $allProductRows
			->map(fn($x) => $x->row)
			->filter()
			->groupBy(function ($row) {
				$coeff = $row->people_coefficient ?? null;
				if (!is_string($coeff)) {
					return 'base';
				}
				$coeff = trim($coeff);
				return $coeff !== '' ? $coeff : 'base';
			})
			->map(function ($rows) {
				return $rows
					->map(fn($r) => $r->calculated_total_row_revenue)
					->filter(fn($v) => is_numeric($v))
					->sum();
			})
			->sortKeys();

		$forfaitTotal = $coeffTotals
			->reject(fn($tot, $coeff) => (string) $coeff === 'base')
			->sum();
		$rowsNetOfForfait = $rowsTotalRevenue - $forfaitTotal;
		$containerTotalCost = $container->total_revenue ?? null;

		$rawPeopleCoefficients = $container->people_coefficient ?? null;
		if (is_string($rawPeopleCoefficients)) {
			$rawPeopleCoefficientsTrimmed = trim($rawPeopleCoefficients);
			if ($rawPeopleCoefficientsTrimmed !== '' && !str_starts_with($rawPeopleCoefficientsTrimmed, '[')) {
				$rawPeopleCoefficientsTrimmed = '[' . $rawPeopleCoefficientsTrimmed . ']';
			}
			$decodedPeopleCoefficients = json_decode($rawPeopleCoefficientsTrimmed, true);
		} elseif (is_array($rawPeopleCoefficients)) {
			$decodedPeopleCoefficients = $rawPeopleCoefficients;
		} else {
			$decodedPeopleCoefficients = null;
		}

		$peopleCoefficients = collect(is_array($decodedPeopleCoefficients) ? $decodedPeopleCoefficients : [])
			->filter(fn($p) => is_array($p) && !empty($p['name']))
			->values();

		$peopleQuantityByName = $peopleCoefficients
			->mapWithKeys(function ($p) {
				$name = (string) ($p['name'] ?? '');
				$qty = $p['quantity'] ?? null;
				$qty = is_numeric($qty) ? (float) $qty : null;
				return [$name => $qty];
			})
			->all();

		$totalPeopleQuantity = collect($peopleQuantityByName)
			->filter(fn($v) => is_numeric($v) && (float) $v > 0)
			->sum();

		// the rows are already grouped by people_coefficient via the container getter
	@endphp

	<table class="doc-table">

		@include('products::pdf.quotations._tableIntestation', [
			'title' => 'Prodotti'
			])

		<tbody>
			@foreach($phaseGroupedProductRows as $rowCoeff => $rows)

				@include('products::pdf.quotations._summaryHeaderRow', [
					'type' => 'product',
					'rows' => $rows,
					'quantity' => $container->getQuantityByPeopleCoefficient($rowCoeff)
				])

				@foreach($rows as $row)

					@include('products::pdf.quotations._row', [
						'type' => 'product',
						'target' => $row->getSellable()?->getTarget(),
						'imageDataUri' => $row->getPdfImageSrc()
					])

				@endforeach

				@include('products::pdf.quotations._summaryFooterRow', [
					'rows' => $rows,
					'quantity' => $container->getQuantityByPeopleCoefficient($rowCoeff)
				])

			@endforeach
		</tbody>
	</table>

	@if(isset($accessoryRows) && $accessoryRows->isNotEmpty())

		<table class="doc-table" style="margin-top: 10px;">

		@include('products::pdf.quotations._tableIntestation', [
			'title' => 'Accessori/Attrezzature/Vari'
			])

			<tbody>
				@foreach($accessoryRows as $row)

					@include('products::pdf.quotations._row', [
						'type' => 'accessory',
						'target' => $row->getSupplier()?->getTarget() ?? $row->getSellable()?->getTarget(),
						'imageDataUri' => $row->getPdfBySupplierImageSrc()
					])

				@endforeach

				@include('products::pdf.quotations._summaryFooterRow', [
					'rows' => $accessoryRows,
					'rowCoeff' => ' Accessori/Attrezzature/Vari'
				])

			</tbody>
		</table>
	@endif

	<table class="doc-table border-top-dark riepiloghi" style="margin-top: 35px;">
		<thead>
			<tr>
				<th>Consuntivi</th>
				<th class="quantity">Quant.</th>
				<th class="single-cost">Costo unit.</th>
				<th class="total-cost">Imponibile</th>
				<th class="vat-percent">Tot IVA</th>
				<th class="total-vatted">Totale Documento</th>
			</tr>
		</thead>
		<tbody>
			@if(count($coeffTotals) > 1)
				@if($coeffTotals->isNotEmpty())
					@foreach($coeffTotals as $coeff => $tot)
						@php
							$coeffQty = $peopleQuantityByName[$coeff] ?? null;
							$unit = (is_numeric($tot) && is_numeric($coeffQty) && (float) $coeffQty > 0) ? ((float) $tot / (float) $coeffQty) : null;
						@endphp
						<tr>
							<td>{{ $coeff }}</td>
							<td class="quantity">{{ $coeffQty ? rtrim(rtrim(number_format((float) $coeffQty, 2, ',', '.'), '0'), ',') : '-' }}</td>
							<td class="single-cost">{{ $formatEuro($unit) }}</td>
							<td class="total-cost">{{ $formatEuro($tot) }}</td>
							<td class="vat-percent"></td>
							<td class="total-vatted"></td>
						</tr>
					@endforeach
				@endif
			@endif

			<tr>
				<td>Totale menu</td>
				@php $rowsTotalUnit = (is_numeric($rowsTotalRevenue) && is_numeric($totalPeopleQuantity = $container->getBaseQuantity()) && (float) $totalPeopleQuantity > 0) ? ((float) $rowsTotalRevenue / (float) $totalPeopleQuantity) : null; @endphp
				<td class="quantity">{{ $totalPeopleQuantity }}</td>
				<td class="single-cost">{{ $formatEuro($rowsTotalUnit) }}</td>
				<td class="total-cost">{{ $formatEuro($container->getTotalRevenueBesidesDiscount()) }}</td>
				<td class="vat-percent"></td>
				<td class="total-vatted"></td>
			</tr>

			<tr>
				<td>Allestimento/personale/logistica</td>
				<td class="quantity"></td>
				<td class="single-cost"></td>
				<td class="total-cost">{{ $formatEuro($container->getTotalRevenueBesidesProducts()) }}</td>
				<td class="vat-percent"></td>
				<td class="total-vatted"></td>
			</tr>

			<tr class="total">
				<td>Costo</td>
				<td class="quantity"></td>
				<td class="single-cost"></td>
				<td class="total-cost">{{ $formatEuro($containerTotalCost) }}</td>
				<td class="vat-percent">{{ $formatEuro($container->getTotalVat()) }}</td>
				<td class="total-vatted">{{ $formatEuro($containerTotalCost + $container->getTotalVat()) }}</td>
			</tr>

		</tbody>
	</table>

	<div class="footer-note">
		Le immagini sono a puro scopo illustrativo e i prodotti si intendono a Pezzo.<br>
		Con l'accettazione del preventivo si accettano incondizionatamente le condizioni e i termini di pagamento, prezzi iva esclusa salvo dove indicato.<br />
		Ogni ora oltre il termine stabilito dell'evento verrà aggiunto un costo di 40€/ora per ogni operatore
	</div>



@if(count($allergens = $container->getAllergensList()) > 0)

	<table>
		<tr>
			<th>Allergeni in questo menu</th>
		</tr>

		@foreach($allergens as $allergen)
		<tr>
			<td>{{ $allergen->getName() }}</td>
		</tr>
		@endforeach

	</table>

@endif


	<table class="pagamento">
		<tr>
			<th class="banca">Pagamento</th>
			<th>Acconto</th>
		</tr>
		<tr>
			<td class="banca">
				Bonifico vista fattura<br />
				UNICREDIT BANCA<br />
				Iban: IT32 Z020 0861 7200 0010 1057 796
			</td>
			<td></td>
		</tr>
	</table>

	@include('products::pdf._contractDocuments')
</body>
</html>
