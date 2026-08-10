<table class="uk-table uk-table-small uk-table-divider uk-table-striped">
	<thead>
		<tr>
			<th>{{ trans('products::fields.sellable') }}</th>
			<th>{{ trans('products::fields.supplier_name') }}</th>
			<th class="uk-text-right">{{ trans('products::fields.calculated_cost_company_total') }}</th>
		</tr>
	</thead>
	<tbody>
	@foreach($children as $child)
		<tr>
			<td>{{ $child->getSellable()->getName() }}</td>
			<td>{{ $child->getSupplierName() }}</td>
			<td class="uk-text-right">{{ number_format($child->calculated_cost_company_total, 2, ',', '.') }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
