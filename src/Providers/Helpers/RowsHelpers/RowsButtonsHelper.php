<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Buttons\Button;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

use function ucfirst;

class RowsButtonsHelper
{
	static function getAddTypedRowButtonSimpleGET(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$type = ucfirst($type);

		$urlGetter = "getAdd{$type}Url";

		$button = Button::create([
			'href' => $container->{$urlGetter}(),
			'text' => "products::orders.add{$type}Row",
			'icon' => 'plus'
		]);

		$button->setSecondary();

		return $button;
	}



	static function getAddTypedRowButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$type = ucfirst($type);

		$urlGetter = "getAdd{$type}Url";

		$button = Button::create([
			'href' => $container->{$urlGetter}(),
			'text' => "products::orders.add{$type}Row",
			'icon' => 'plus'
		]);

		$button->setSecondary();

		$button->setAjaxTableButton(null, [
			'openIframe' => true
		]);

		return $button;
	}

	static function getAddTypedRowTableButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$url = $container->getAddRowByTypeUrl($type, true);

		$button = Button::create([
			'href' => $url,
			'text' => "products::orders.add{$type}Row",
			'icon' => 'plus'
		]);

		$button->setSecondary();

		$button->setAjaxTableButton(null, [
			'openIframe' => true
		]);

		return $button;
	}


}