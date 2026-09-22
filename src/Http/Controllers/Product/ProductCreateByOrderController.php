<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use Illuminate\Http\Request;

class ProductCreateByOrderController extends ProductCreateController
{
	public $allowedMethods = ['createByOrder', 'storeByOrder'];

	protected Order $order;

	protected function setOrder(string $order) : void
	{
		$this->order = Order::gpc()::findOrFail($order);

		abort_unless($this->order->userCanUpdate(), 403);
		abort_unless($this->getModelClass()::userCanCreate(), 403);
		abort_if($this->order->isFrozen(), 403, __('products::orders.cannotCreateProductOnFrozenOrder'));
		abort_unless(is_a($this->getModelClass(), SellableItemInterface::class, true), 404);
	}

	public function createByOrder(string $order)
	{
		$this->setOrder($order);

		return parent::create();
	}

	public function storeByOrder(Request $request, string $order)
	{
		$this->setOrder($order);

		return $this->order->getConnection()->transaction(function () use ($request)
		{
			return $this->_store($request);
		});
	}

	public function getStoreModelAction()
	{
		return app('products')->route('orders.storeProduct', ['order' => $this->order->getKey()]);
	}

	public function performAdditionalOperations()
	{
		parent::performAdditionalOperations();

		$product = $this->getModel();
		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget(
			$product, [], $product->getSellableTypeName()
		);

		// The catering product supplies the owner company as its default supplier.
		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier(
			$product->getPossibleSuppliers()->sole(), $sellable
		);

		RowAssociatorHelper::associateRowBySellableSupplier($this->order, $sellableSupplier);
	}

	protected function shouldCloseIframeAfterStore(Request $request) : bool
	{
		return $this->isIframed();
	}

	protected function closeIframeAfterStore(Request $request)
	{
		return view('crud::utilities.messages.closeIframe', [
			'closeMessage' => __('products::orders.productCreatedAndAdded'),
			'reloadAllTables' => true,
		]);
	}

	public function getAfterStoredRedirectUrl()
	{
		return $this->order->getEditUrl();
	}
}
