<?php

namespace IlBronza\Products\Providers\Buttons;

use IlBronza\Buttons\Button;

use function config;
use function json_encode;
use function trans;

class AddRowSelectButton extends Button
{
	public array $sellablesList = [];
	public ?string $associateUrlTemplate = null;

	public function setSellablesList(array $sellablesList) : static
	{
		$this->sellablesList = $sellablesList;

		return $this;
	}

	public function setAssociateUrlTemplate(string $associateUrlTemplate) : static
	{
		$this->associateUrlTemplate = $associateUrlTemplate;

		return $this;
	}

	public function getSellableIdPlaceholder() : string
	{
		return config('datatables.replace_model_id_string');
	}

	/**
	 * JS eseguito come action del bottone datatables:
	 * monta un select2 volante con la lista dei sellables del tipo e,
	 * alla scelta, chiama la route dedicata e ricarica le tabelle interessate.
	 *
	 * @return string
	 **/
	public function renderJsMethod()
	{
		$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$options = json_encode($this->sellablesList, $jsonFlags);
		$urlTemplate = json_encode($this->associateUrlTemplate, $jsonFlags);
		$idPlaceholder = json_encode($this->getSellableIdPlaceholder(), $jsonFlags);
		$placeholderText = json_encode(trans('products::sellables.pickSellable'), $jsonFlags);

		return <<<JS
window.__ibMountAddRowSelect = window.__ibMountAddRowSelect || function (node, options, urlTemplate, idPlaceholder, placeholderText)
{
	jQuery('.ib-add-row-select-floating').remove();

	var \$button = jQuery(node);
	var offset = \$button.offset();

	var \$container = jQuery('<div class="ib-add-row-select-floating"></div>').css({
		position: 'absolute',
		top: offset.top + \$button.outerHeight() + 4,
		left: offset.left,
		zIndex: 10090,
		minWidth: '320px',
		background: '#fff',
		padding: '8px',
		boxShadow: '0 5px 15px rgba(0,0,0,.15)'
	});

	var \$select = jQuery('<select style="width: 100%;"></select>');

	\$select.append(new Option('', '', true, true));

	jQuery.each(options, function (id, name)
	{
		\$select.append(new Option(name, id, false, false));
	});

	\$container.append(\$select);
	jQuery('body').append(\$container);

	var removeContainer = function ()
	{
		if (\$select.data('select2'))
			\$select.select2('destroy');

		\$container.remove();
	};

	\$select.select2({
		dropdownParent: \$container,
		placeholder: placeholderText,
		width: '100%'
	});

	\$select.on('select2:select', function (e)
	{
		var sellableId = e.params.data.id;

		if (! sellableId)
			return;

		jQuery.ajax({
			url: urlTemplate.replace(idPlaceholder, sellableId),
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json'
			}
		}).done(function (response)
		{
			jQuery.each((response && response.tablesToRefresh) || [], function (i, tableClass)
			{
				try
				{
					window.___reloadTable(window.__getDataTableByClass(tableClass));
				}
				catch (err)
				{
					console.error(err);
				}
			});
		}).always(removeContainer);
	});

	\$select.on('select2:close', function ()
	{
		setTimeout(function ()
		{
			if (! \$select.val())
				removeContainer();
		}, 150);
	});

	\$select.select2('open');
};

window.__ibMountAddRowSelect(node, {$options}, {$urlTemplate}, {$idPlaceholder}, {$placeholderText});
JS;
	}
}
