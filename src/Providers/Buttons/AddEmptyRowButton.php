<?php

namespace IlBronza\Products\Providers\Buttons;

use IlBronza\Buttons\Button;

use function json_encode;

class AddEmptyRowButton extends Button
{
	public string $createUrl;

	public function setCreateUrl(string $createUrl) : static
	{
		$this->createUrl = $createUrl;

		return $this;
	}

	public function getCreateUrl() : string
	{
		return $this->createUrl;
	}

	/**
	 * JS eseguito come action del bottone datatables:
	 * chiama la route che crea la riga vuota e ricarica le tabelle interessate
	 *
	 * @return string
	 **/
	public function renderJsMethod()
	{
		$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;

		$url = json_encode($this->getCreateUrl(), $jsonFlags);

		return <<<JS
window.__ibAddEmptyRow = window.__ibAddEmptyRow || function (url)
{
	jQuery.ajax({
		url: url,
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
	});
};

window.__ibAddEmptyRow({$url});
JS;
	}
}
