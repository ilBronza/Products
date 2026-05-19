<script type="text/javascript">
jQuery(document).ready(function ($)
{
	
	@foreach($modelInstance->getFieldsToUpdateOnTableEdit() as $field)
		window.dtEditorRefreshingFieldList.push('input[name={{ $field }}]');
	@endforeach

	var peopleCoefficientCalculatedPricesSyncTimer = null;

	function parseCalculatedTotalRowRevenue($el)
	{
		if (!$el || !$el.length)
			return 0;

		var raw = $el.val();
		if (raw === undefined || raw === null || raw === '')
			raw = $el.attr('value');

		raw = String(raw).replace(/\s/g, '').replace(',', '.');
		var n = parseFloat(raw);

		return isNaN(n) ? 0 : n;
	}

	/**
	 * Il select può avere option value="null" (stringa): .val() restituisce "null", che è truthy.
	 * In quel caso va usato il coefficiente "base" come per valore vuoto.
	 */
	function resolvePeopleCoefficientNameForRow(rawVal)
	{
		if (rawVal === undefined || rawVal === null)
			return 'base';

		var s = String(rawVal).trim();

		if (!s)
			return 'base';

		var lower = s.toLowerCase();

		if (lower === 'null' || lower === 'undefined' || lower === 'nd')
			return 'base';

		return s;
	}

	/**
	 * Il select può avere option value="null" (stringa): .val() non è null JS ma "null",
	 * quindi va normalizzato al fallback "base" come per valore vuoto.
	 */
	function peopleCoefficientKeyFromSelectVal(rawVal)
	{
		if (rawVal === undefined || rawVal === null)
			return 'base';

		var s = String(rawVal).trim();

		if (!s)
			return 'base';

		var lower = s.toLowerCase();

		if (lower === 'null' || lower === 'undefined')
			return 'base';

		return s;
	}

	function syncPeopleCoefficientCalculatedPricesFromProductRows()
	{
		var $table = $('table.productRows');

		if (!$table.length)
			return;

		var sums = Object.create(null);

		$table.find('tbody tr').each(function ()
		{
			var $tr = $(this);

			if ($tr.hasClass('child') || $tr.hasClass('dtrg-group'))
				return;

			var $peopleSelect = $tr.find('select[data-field="people_coefficient"]');

			if (!$peopleSelect.length)
				return;

			var coefficientName = resolvePeopleCoefficientNameForRow($peopleSelect.val());

			var $revenueField = $tr.find('[data-field="calculated_total_row_revenue"]');
			var amount = parseCalculatedTotalRowRevenue($revenueField);

			sums[coefficientName] = (sums[coefficientName] || 0) + amount;
		});

		var $jsonEditor = $('.ib-json-editor[data-fieldname="people_coefficient"]');

		if (!$jsonEditor.length)
			return;

		$jsonEditor.find('.valuescontainer .jsonvalues').each(function ()
		{
			var $jsonRow = $(this);
			var $nameInput = $jsonRow.find('input[name="people_coefficient[][name]"]');
			var $calculatedInput = $jsonRow.find('input[name="people_coefficient[][calculated_price]"]');

			if (!$nameInput.length || !$calculatedInput.length)
				return;

			var jsonName = String($nameInput.val() || '').trim();
			var total = jsonName ? (sums[jsonName] || 0) : 0;
			var formatted = total.toFixed(2);

			if ($calculatedInput.val() !== formatted)
				$calculatedInput.val(formatted).trigger('change');
		});
	}

	function scheduleSyncPeopleCoefficientCalculatedPrices()
	{
		clearTimeout(peopleCoefficientCalculatedPricesSyncTimer);
		peopleCoefficientCalculatedPricesSyncTimer = setTimeout(syncPeopleCoefficientCalculatedPricesFromProductRows, 75);
	}

	$(document).on(
		'change',
		'table.productRows select[data-field="people_coefficient"]',
		scheduleSyncPeopleCoefficientCalculatedPrices
	);

	$(document).on(
		'change input blur',
		'table.productRows [data-field="calculated_total_row_revenue"]',
		scheduleSyncPeopleCoefficientCalculatedPrices
	);

	$(document).on('draw.dt xhr.dt', 'table.productRows', scheduleSyncPeopleCoefficientCalculatedPrices);

	$(document).on(
		'change blur',
		'.ib-json-editor[data-fieldname="people_coefficient"] .valuescontainer input[name="people_coefficient[][name]"]',
		scheduleSyncPeopleCoefficientCalculatedPrices
	);

	syncPeopleCoefficientCalculatedPricesFromProductRows();
	setTimeout(syncPeopleCoefficientCalculatedPricesFromProductRows, 400);

	function resolveDiscountSelection(rawVal)
	{
		if (rawVal === undefined || rawVal === null)
			return 'discount_neat';

		var s = String(rawVal).trim();

		if (!s)
			return 'discount_neat';

		var lower = s.toLowerCase();

		if (lower === 'null' || lower === 'undefined')
			return 'discount_neat';

		return s;
	}

	function getDiscountFieldContainer(fieldName)
	{
		var $input = $('[name="' + fieldName + '"], [data-field="' + fieldName + '"]').first();

		if (!$input.length)
			return $();

		var $container = $input.closest('.fieldcontainer');

		if ($container.length)
			return $container;

		return $input.closest('.uk-margin, .uk-form-controls').parent();
	}

	function toggleDiscountField(fieldName, visible)
	{
		var $container = getDiscountFieldContainer(fieldName);

		if (!$container.length)
			return;

		if (visible)
			$container.removeClass('uk-hidden').show();
		else
			$container.addClass('uk-hidden').hide();
	}

	function syncDiscountFieldsVisibility()
	{
		var $selection = $('select[name="discount_selection"], [data-field="discount_selection"]').first();

		if (!$selection.length)
			return;

		var selection = resolveDiscountSelection($selection.val());

		toggleDiscountField('discount_neat', selection === 'discount_neat');
		toggleDiscountField('discount_percentage', selection === 'discount_percentage');
	}

	function scheduleSyncDiscountFieldsVisibility()
	{
		setTimeout(syncDiscountFieldsVisibility, 0);
	}

	$(document).on(
		'change',
		'select[name="discount_selection"], [data-field="discount_selection"]',
		scheduleSyncDiscountFieldsVisibility
	);

	$(document).ajaxComplete(function ()
	{
		scheduleSyncDiscountFieldsVisibility();
	});

	syncDiscountFieldsVisibility();
	setTimeout(syncDiscountFieldsVisibility, 400);

});
</script>