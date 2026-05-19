<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Buttons\Button;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

use function preg_replace;
use function ucfirst;

class RowsButtonsHelper
{
	private static function iconKeyForText(string $text) : string
	{
		return preg_replace('/::(rows|orders)\./', '::icons.', $text);
	}

	private static function makeButton(string $href, string $text, bool $openIframe = false) : Button
	{
		$button = Button::create([
			'href' => $href,
			'text' => $text,
			'icon' => static::iconKeyForText($text),
		]);

		$button->setSecondary();

		if($openIframe)
			$button->setAjaxTableButton(null, ['openIframe' => true]);

		return $button;
	}

	static function getAddTypedRowButtonSimpleGET(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$type = ucfirst($type);

		return static::makeButton(
			$container->{"getAdd{$type}Url"}(),
			"products::orders.add{$type}Row"
		);
	}

	static function getAddTypedRowButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$type = ucfirst($type);

		return static::makeButton(
			$container->{"getAdd{$type}Url"}(),
			'products::rows.addRow',
			true
		);
	}

	static function getAddTypedRowTableButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		return static::makeButton(
			$container->getAddRowByTypeUrl($type, true),
			'products::rows.addTableRow',
			true
		);
	}

	static function getAddSellableSupplierButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		return static::makeButton(
			$container->getAddSellableSupplierRowByTypeUrl($type),
			'products::rows.addSellableSupplierRow',
			true
		);
	}

	static function getAddSupplierButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		return static::makeButton(
			$container->getAddSupplierRowByTypeUrl($type),
			'products::rows.addSupplierRow',
			true
		);
	}
}
