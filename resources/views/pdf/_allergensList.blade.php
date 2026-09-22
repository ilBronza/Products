@if(method_exists($container, 'getAllergensList') && ($allergens = $container->getAllergensList())->isNotEmpty())
	<h2>@lang('products::catering.allergens.title')</h2>
	<table>
		<thead>
			<tr>
				<th>@lang('products::fields.allergens')</th>
			</tr>
		</thead>
		<tbody>
			@foreach($allergens as $allergen)
				<tr>
					<td>{{ $allergen->renderText() }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endif
