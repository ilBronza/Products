{{-- Parte listino --}}
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
								<img src="{{ $imageUrl }}" alt="" class="product-thumb uk-margin-small-right">
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
