<div class="uk-container uk-container-default uk-margin-remove-vertical">
	<div class="uk-grid tm-grid-expand uk-flex-top" uk-grid="parallax: 0; parallax-justify: true; parallax-start: 100vh; parallax-end: 100vh;">

		@include('products::html-preview._phases')
		@include('products::html-preview._fullList')
		@include('products::html-preview._images')
	</div>
</div>

<div class="quotation-preview uk-section uk-padding-remove-vertical" style="background: #0d0e10; min-height: 100vh; color: #e4e6eb;">
	<div class="uk-container uk-container-large content-wrap uk-padding">
		<div class="uk-grid-large" uk-grid>
			<div class="uk-width-1-1 uk-width-1-3@m">
				<div class="info-card uk-margin-medium-bottom">
					<h3>Destinatario</h3>
					<p class="uk-margin-remove uk-text-large" style="font-weight: 600;">{{ $clientName }}</p>
				</div>
				@if($dataEvento || $location || $pax)
				<div class="info-card">
					<h3>Dettagli Evento</h3>
					@if($dataEvento)
						<p class="uk-margin-small"><strong>Data:</strong> {{ \Carbon\Carbon::parse($dataEvento)->format('d F Y') }}</p>
					@endif
					@if($location)
						<p class="uk-margin-small"><strong>Location:</strong> {{ $location }}</p>
					@endif
					@if($pax)
						<p class="uk-margin-small"><strong>Pax:</strong> {{ $pax }}</p>
					@endif
				</div>
				@endif
			</div>

			<div class="uk-width-1-1 uk-width-2-3@m">
				<div class="table-card">
					<table>
						<thead>
							<tr>
								<th>Prodotto</th>
								<th class="uk-text-center">Qtà</th>
								<th class="uk-text-right">Prezzo</th>
								<th class="uk-text-right">Importo</th>
							</tr>
						</thead>
						<tbody>
							@foreach($allRows as $item)
								@php
									$row = $item->row;
									$product = $row->getSellable()?->getTarget();
									$imageUrl = null;
									if ($product && method_exists($product, 'getFirstMedia')) {
										$media = $product->getFirstMedia('default');
										if ($media) {
											$imageUrl = $media->getUrl();
										}
									}
									$productCode = $product && method_exists($product, 'getAttribute') ? ($product->slug ?? $product->getKey()) : '-';
									$productName = $row->getSellableName() ?? '-';
									$quantity = $row->getQuantity() ?? '-';
									$price = $row->price ?? $row->client_price ?? '-';
									$total = $row->total_price ?? $row->total_cost ?? '-';
									if (is_numeric($total)) $total = number_format($total, 2, ',', '.');
									if (is_numeric($price)) $price = number_format($price, 2, ',', '.');
								@endphp
								<tr>
									<td>
										<div class="uk-flex uk-flex-middle">
											@if($imageUrl)
												<img src="/images/{{ $imageUrl }}" alt="" class="product-thumb uk-margin-small-right">
											@endif
											<div>
												<span class="product-code">{{ $productCode }}</span>
												<span>{{ $productName }}</span>
											</div>
										</div>
									</td>
									<td class="uk-text-center">{{ $quantity }}</td>
									<td class="uk-text-right">€ {{ $price }}</td>
									<td class="uk-text-right text-gold"><strong>€ {{ $total }}</strong></td>
								</tr>
							@endforeach
						</tbody>
					</table>
					@if($allRows->isEmpty())
						<div class="uk-padding uk-text-center" style="color: var(--text-muted);">Nessuna riga presente.</div>
					@endif
				</div>

				<div class="footer-note">
					{{ $footerText ?? '' }}
				</div>
			</div>
		</div>
	</div>
</div>
