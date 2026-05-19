<tr class="tr-phase-title border-top margin-bottom-row">
	<td colspan="2"></td>
	<td colspan="3">
		Riepilogo per {{ $rowCoeff }}
	</td>
	<td class="product-price">
		{{ $formatEuro($rows->sum(function($row)
					{
						if(is_numeric($value = $row->getPdfTotalRevenue()))
							return $value;
		
						return 0;
					})) }}
	</td>
	<td> - </td>
	<td class="product-price">
		{{ $formatEuro($rows->sum(function($row)
					{
						if(is_numeric($value = $row->getPdfTotalVatted()))
							return $value;
		
						return 0;
					})) }}
	</td>
</tr>
<tr>
	<td colspan="8">&nbsp;</td>
</tr>
