<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\CRUD\Interfaces\CrudReorderableModelInterface;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Payments\Models\Interfaces\InvoiceDetailInterface;
use IlBronza\Products\Models\Interfaces\RowInterface;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\Traits\Order\CommonOrderrowQuotationrowTrait;
use IlBronza\Products\Models\Traits\Orderrow\CommonOrderrowQuotationrowCheckersTrait;
use IlBronza\Products\Models\Traits\Orderrow\CommonOrderrowQuotationrowGettersTrait;
use IlBronza\Products\Models\Traits\Orderrow\CommonOrderrowQuotationrowPricesTrait;
use IlBronza\Products\Models\Traits\Orderrow\OrderrowRelationsScopesTrait;
use IlBronza\Timings\Interfaces\TimeIntervalInterface;
use IlBronza\Timings\Interfaces\TimelineInterface;
use function rand;

class Orderrow extends ProductPackageBaseRowModel implements CrudReorderableModelInterface, InvoiceDetailInterface, TimelineInterface, RowInterface
{
	static $modelConfigPrefix = 'orderrow';

	use CommonOrderrowQuotationrowTrait;
	use CommonOrderrowQuotationrowCheckersTrait;
	use CommonOrderrowQuotationrowPricesTrait;
	use CommonOrderrowQuotationrowGettersTrait;

	use OrderrowRelationsScopesTrait;

	public $classnameAbbreviation = 'or';

	// public function getTotalRowCostAttribute() : float
	// {
	// 	//Fare l'override di questa o capire come renderla astratta, forse orderrows va eliminata come relazione, può essere? Forse order e order per i sellable va creata in modo diverso, una ha la relazione orderrows e una no?
	// 	return 9999999;
	// }

	public function getModelContainer() : ?Order
	{
		return $this->getOrder();
	}

	public function getOrder() : ?Order
	{
		return $this->order;
	}

	public function container()
	{
		return $this->order();
	}

	public function getModelContainerRelationName() : string
	{
		return 'order';
	}

	public function getTimelineHtmlClasses() : array
	{
		$pieces = [];

		return $pieces;
	}

	public function getTimelineHtmlClassesString() : ? string
	{
		$pieces = $this->getTimelineHtmlClasses();

		return implode(' ', $pieces);
	}

	public function getCompletionPercentage() : float
	{
		if(rand(0,1))
			return 100;

		if(rand(0,1))
			return 0;

		return rand(0, 100);
	}

	public function getSplitUrl() : string
	{
		return $this->getKeyedRoute('split') . '?closeIframe=1';
	}

	public function getSplitWeekendsUrl() : string
	{
		return $this->getKeyedRoute('splitWeekends') . '?closeIframe=1';
	}

	public function getDeleteUrl(array $data = []) : string
	{
		return $this->getKeyedRoute('destroy', $data);
	}

	public function getTimelineItemRightLinks(? TimelineGroupInterface $groupModel) : array
	{
		$rightLinks = parent::getTimelineItemRightLinks($groupModel);

		if ($url = $this->getSplitUrl()) {
			$rightLinks[] = [
				'url' => $url,
				'text' => __('products::orderrows.split'),
				'faIcon' => 'scissors',
			];
		}

		if ($url = $this->getSplitWeekendsUrl()) {
			$rightLinks[] = [
				'url' => $url,
				'text' => __('products::orderrows.splitWeekends'),
				'faIcon' => 'calendar-week',
			];
		}

		if ($url = $this->getDeleteUrl()) {
			$rightLinks[] = [
				'url' => $url,
				'method' => 'DELETE',
				'text' => __('products::orderrows.delete'),
				'faIcon' => 'trash',
				'htmlClasses' => ['uk-button-danger'],
			];
		}

		return $rightLinks;
	}

	public function genericChildren()
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function genericParent()
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function getGenericParent() : ? self
	{
		return $this->genericParent;
	}
}