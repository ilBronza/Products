@if($row->isPdfPrintable())
<tr class="row-product" style="page-break-inside: avoid !important;">
	<td class="col-img">
		 @if ($imageDataUri)
			<div class="thumb-frame">
				<img src="{{ $imageDataUri }}" style="{{ $row->getPdfImageThumbStyle(110) }}" />
			</div>
		@else
			&nbsp;
		@endif
	</td>
	<td class="code">
		{{ $target->slug }}
	</td>
	<td class="description">
		<strong>{{ ucfirst($target->getName()) }}</strong>
		 @if ($description = $target->short_description)
			<div style="font-size:8px;color:#555;white-space:pre-line;margin-top:2px;">{{ ucfirst($description) }}</div>
		@endif

		@if ($description = $row->getPdfDescription())
			<div style="font-size:8px;color:#555;white-space:pre-line;margin-top:2px;">{!! $description !!}</div>
		@endif


	</td>
	<td class="quantity">{{ $row->getPdfQuantity() ?? '-' }}</td>
	<td class="single-cost">
		{{ $formatEuro($row->getPdfSingleRevenue()) }}
	</td>
	<td class="total-cost">
		@if(is_numeric($row->getPdfTotalRevenue()))
		{{ $formatEuro($row->getPdfTotalRevenue()) }}
		@else
		{!! $row->getPdfTotalRevenue() !!}
		@endif
	</td>
	<td class="vat-percent">
		{{ $row->getPdfVat() }}
	</td>
	<td class="total-vatted">
		{{ $formatEuro($row->getPdfTotalVatted()) }}
	</td>
</tr>
@endif