<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

use function config;

class OrderEditUpdateController extends OrderCRUD
{
	use CRUDEditUpdateTrait;

	public ?bool $updateEditor = false;

	public $allowedMethods = ['edit', 'update'];

	public function getRelationshipsManagerClass()
	{
		return $this->getModel()->getEditRelationshipsManagerClass();
	}

	public function getGenericParametersFile() : ?string
	{
		return config('products.models.order.parametersFiles.edit');
	}

	public function edit($order)
	{
		$order = $this->findModel($order);

		if (config('products.models.order.hasGantt', false))
			$this->addNavbarButton(
				$order->getGanttButton()
			);

		if (config('products.models.order.hasCalendar', false))
			$this->addNavbarButton(
				$order->getCalendarButton()
			);

		if(config('products.models.order.buttons.changeClient'))
			if (! $order->isFrozen())
				$this->addNavbarButton(
					$order->getChangeClientButton()
				);

		if(config('products.models.order.buttons.pdfButton'))
			$this->addNavbarButton($order->getPdfButton());

		if(config('products.models.order.buttons.htmlPreviewButton'))
			$this->addNavbarButton($order->getHtmlPreviewButton());

		if(config('products.models.order.buttons.resetRowsIndexesButton'))
			$this->addNavbarButton($order->getResetRowsIndexesButton());

		if(config('products.models.order.buttons.attachClientOperatorsToOrderrows'))
			$this->addNavbarButton($order->getAttachClientOperatorsToOrderrowsButton());

		if(config('products.models.order.buttons.freeze'))
			if (! $order->isFrozen())
				$this->addNavbarButton($order->getFreezeButton());

		return $this->_edit($order);
	}

	public function update(Request $request, $order)
	{
		$order = $this->findModel($order);

		return $this->_update($request, $order);
	}
}
