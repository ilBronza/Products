@extends('uikittemplate::app')

@section('content')

<div class="uk-container uk-container-large uk-padding">
	<div class="uk-flex uk-flex-between uk-flex-middle uk-margin-bottom">
		<h3 class="uk-margin-remove">{{ $pageTitle }}</h3>
	</div>

	<form method="POST" action="{{ $duplicateAction }}" class="rowcontainer-duplicate-relations-form">
		@csrf

		<input type="hidden" name="event_starts_at" value="{{ $eventStartsAt }}" />

		@foreach($relationBlocks as $block)
			<div class="uk-card uk-card-default uk-card-body uk-margin-bottom" data-relation-key="{{ $block['key'] }}">
				<input type="hidden" name="relations[{{ $block['key'] }}][_submitted]" value="1" />
				<div class="uk-flex uk-flex-middle uk-margin-small-bottom">
					<label class="uk-flex uk-flex-middle uk-width-1-1">
						<input
							type="checkbox"
							class="uk-checkbox uk-margin-small-right relation-master-checkbox"
							name="relations[{{ $block['key'] }}][enabled]"
							value="1"
							data-relation-key="{{ $block['key'] }}"
							@if($block['defaultSelected']) checked @endif
						/>
						<span class="uk-text-bold uk-text-large">{{ $block['label'] }}</span>
						<span class="uk-margin-small-left uk-text-meta">({{ count($block['instances']) }})</span>
					</label>
				</div>

				@if($block['multiple'] && count($block['instances']))
					<div class="uk-overflow-auto">
						<table class="uk-table uk-table-small uk-table-divider uk-table-hover uk-margin-remove">
							@if(count($block['columns']))
								<thead>
								<tr>
									<th class="uk-table-shrink"></th>
									@foreach($block['columns'] as $column)
										<th>{{ $column['label'] }}</th>
									@endforeach
								</tr>
								</thead>
							@endif
							<tbody>
							@foreach($block['instances'] as $instance)
								<tr>
									<td class="uk-table-shrink">
										<input
											type="checkbox"
											class="uk-checkbox relation-instance-checkbox"
											name="relations[{{ $block['key'] }}][instances][]"
											value="{{ $instance['id'] }}"
											data-relation-key="{{ $block['key'] }}"
											@if($block['defaultSelected']) checked @endif
										/>
									</td>
									@if(count($block['columns']))
										@foreach($instance['cells'] as $cell)
											<td>{{ $cell['value'] }}</td>
										@endforeach
									@else
										<td>{{ $instance['label'] }}</td>
									@endif
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				@elseif(! $block['multiple'] && count($block['instances']))
					@foreach($block['instances'] as $instance)
						<input type="hidden" name="relations[{{ $block['key'] }}][instances][]" value="{{ $instance['id'] }}" />
						<p class="uk-margin-remove uk-text-meta">{{ $instance['label'] }}</p>
					@endforeach
				@else
					<p class="uk-margin-remove uk-text-meta uk-text-italic">{{ $emptyRelationsMessage }}</p>
				@endif
			</div>
		@endforeach

		<div class="uk-flex uk-flex-right uk-margin-top">
			<a href="{{ $rowContainer->getEditUrl() }}" class="uk-button uk-button-default uk-margin-small-right">
				@lang('form::form.cancel')
			</a>
			<button type="submit" class="uk-button uk-button-primary">
				{{ $confirmLabel }}
			</button>
		</div>
	</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.relation-master-checkbox').forEach(function (master) {
		var relationKey = master.dataset.relationKey;
		var instanceCheckboxes = document.querySelectorAll('.relation-instance-checkbox[data-relation-key="' + relationKey + '"]');

		function syncFromMaster() {
			instanceCheckboxes.forEach(function (checkbox) {
				checkbox.checked = master.checked;
			});
		}

		function syncFromInstances() {
			if (! instanceCheckboxes.length) {
				return;
			}

			var checkedCount = Array.from(instanceCheckboxes).filter(function (checkbox) {
				return checkbox.checked;
			}).length;

			master.checked = checkedCount > 0;
			master.indeterminate = checkedCount > 0 && checkedCount < instanceCheckboxes.length;
		}

		master.addEventListener('change', syncFromMaster);

		instanceCheckboxes.forEach(function (checkbox) {
			checkbox.addEventListener('change', syncFromInstances);
		});

		syncFromMaster();
	});
});
</script>

@endsection
