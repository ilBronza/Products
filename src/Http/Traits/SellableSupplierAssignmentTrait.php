<?php

namespace IlBronza\Products\Http\Traits;

use Exception;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsSellableSupplierAssociatorHelper;
use Illuminate\Support\Facades\Log;
use function compact;
use function config;
use function dd;
use function view;

trait SellableSupplierAssignmentTrait
{
	public $avoidCreateButton = true;
	public function getIndexElements()
	{
		$sellableSupplierHelper = config('products.models.sellableSupplier.helpers.findBySellableHelper');

		return $sellableSupplierHelper::findBySellable($this->sellable);
	}

	public function getTargetType() : string
	{
		return $this->targetType;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function _associateBulkSellableSupplier($target, $sellableSupplier)
	{
		$type = $target->getSellable()->getType();

		$brothers = $target->getModelContainer()->rows()->bySellableType($type)->get();

		foreach($brothers as $brother)
			if(! $brother->getSellableSupplier())
				$this->__associateSellableSupplier($brother, $sellableSupplier);

		$this->setSellable($target->getSellable());

		return $this->closeIframe($this->getTablesToRefresh());
	}

	public function getTablesToRefresh() : array
	{
		$sellable = $this->getSellable();

		$target = $sellable->getTarget();

		return $target->getContainerModelRelatedTablesToRefresh();
	}

	public function __associateSellableSupplier($target, $sellableSupplier) : void
	{
		RowsSellableSupplierAssociatorHelper::associateSellableSupplierToRow($target, $sellableSupplier);
	}

	public function closeIframe($tablesToRefresh)
	{
		return view('datatables::utilities.closeIframe', compact('tablesToRefresh'));
	}

	public function setSellable(Sellable $sellable)
	{
		$this->sellable = $sellable;
	}

	public function _associateSellableSupplier($target, $sellableSupplier)
	{
		$this->__associateSellableSupplier($target, $sellableSupplier);

		$this->setSellable($target->getSellable());

		return $this->closeIframe(
			$this->getTablesToRefresh()
		);
	}

	public function getIndexFieldsArray()
	{
		$lcType = lcfirst($this->getSellable()->getType());

		if(! $helper = config("products.models.sellableSupplier.fieldsGroupsFiles.{$lcType}"))
			throw new \Exception('declare helper class in config ' . "products.models.sellableSupplier.fieldsGroupsFiles.{$lcType}");

		return $helper::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
	}

	public function getContainerModelPrefix() : string
	{
		return $this->containerModelPrefix;
	}

	public function getSortingIndexByType($container, string $type)
	{
		return $container->rows()->bySellableType($type)->max('sorting_index') + 1;
	}

	public function addNewRowBySellableSupplier($container, $sellableSupplier)
	{
		$sellableSupplier = SellableSupplier::gpc()::with('sellable', 'supplier')->find($sellableSupplier);

		$rowSortingIndex = $this->getSortingIndexByType($container, $sellableSupplier->sellable->type);

		$row = $container->rows()->make();		
		$row->sellable()->associate($sellableSupplier->getSellable());
		$row->container()->associate($container);

		$row->type = $sellableSupplier->getSellable()->type;
		$row->sorting_index = $rowSortingIndex ++;

		$row->save();

		return $this->_associateSellableSupplier($row, $sellableSupplier);
	}

}