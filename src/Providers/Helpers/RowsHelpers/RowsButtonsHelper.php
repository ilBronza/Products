<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Buttons\Button;
use IlBronza\Buttons\ElementsSelectRowsButton;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Buttons\AddEmptyRowButton;
use IlBronza\Products\Providers\Buttons\AddRowSelectButton;
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
			"products::rows.addRow{$type}",
			true
		);
	}

	static function getAddTypedRowTableButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		return static::makeButton(
			$container->getAddRowByTypeUrl($type, true),
			"products::rows.addTableRow{$type}",
			true
		);
	}

	static function getAddSellableSupplierButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		return static::makeButton(
			$container->getAddSellableSupplierRowByTypeUrl($type),
			"products::rows.addSellableSupplierRow{$type}",
			true
		);
	}

	static function getAddRowSelectButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$text = "products::rows.addRowSelect{$type}";

		$button = AddRowSelectButton::create([
			'text' => $text,
			'icon' => static::iconKeyForText($text),
		]);

		$button->setSecondary();

		$button->setSellablesList(
			$container->getPossibleSellablesByType($type)
		);

		$button->setAssociateUrlTemplate(
			$container->getAddRowBySellableUrlTemplate()
		);

		return $button;
	}

	static function getAddEmptyRowButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$text = "products::rows.addEmptyRow{$type}";

		$button = AddEmptyRowButton::create([
			'text' => $text,
			'icon' => static::iconKeyForText($text),
		]);

		$button->setSecondary();

		$button->setCreateUrl(
			$container->getAddEmptyRowByTypeUrl($type)
		);

		return $button;
	}

	static function getAddSupplierButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		return static::makeButton(
			$container->getAddSupplierRowByTypeUrl($type),
			"products::rows.addSupplierRow{$type}",
			true
		);
	}



	static function getCreateParentRowByManufacturerTypeButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$button = ElementsSelectRowsButton::create([
	        'text' => "Associa fornitore",
	        'icon' => 'plus'
    	])
        ->setSecondary()
        ->setElements(
            $container->getPossibleManufacturersByType($type) // [id => name]
        )
        ->setPostUrl(
            $container->getCreateParentRowByManufacturerTypeButtonUrl($type)
        );

		return $button;
	}

	static function getAssociateOrCreateParentRowByTypeButton(ProductPackageBaseRowcontainerModel $container, string $type) : Button
	{
		$button = static::makeButton(
			$container->getAssociateOrCreateParentRowByTypeUrl($type),
			"products::rows.associateOrCreateParentRowByType{$type}",
			true
		);

		$button->setAsIframe();
		$button->setSubmitTableButton();

		return $button;
	}

}
