<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use Exception;
use IlBronza\Buttons\Button;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Traits\Assignee\ProductAssignmentTrait;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\Order\CommonOrderQuotationTrait;
use IlBronza\Products\Models\Traits\Order\OrderRelationshipsTrait;
use IlBronza\Products\Models\Traits\Order\OrderScopesTrait;
use IlBronza\Timings\Interfaces\HasTimingInterface;
use IlBronza\Timings\Traits\InteractsWithTimingTrait;
use Illuminate\Support\Collection;
use function app;
use function strtolower;

class Order extends ProductPackageBaseRowcontainerModel implements HasTimingInterface
{
	use InteractsWithTimingTrait;
	use CommonOrderQuotationTrait;

	use CRUDSluggableTrait;

	use OrderRelationshipsTrait;
	use OrderScopesTrait;

	use ProductAssignmentTrait;
	use CompletionScopesTrait;

	static $modelConfigPrefix = 'order';
	static $deletingRelationships = ['orderProducts', 'orderrows'];
	public $classnameAbbreviation = 'o';

	public function getStoreOrderrowUrl() : string
	{
		return $this->getKeyedRoute('storeOrderrow', [
			'quotation' => $this->getKey(),
		]);
	}

	public function getTimingChildren() : Collection
	{
		$result = $this->getChildren();
		$result = $result->merge($this->getOrderrows());

		return $result->merge($this->getOrderProducts());
	}

	public function getQuantityRequired() : ?float
	{
		return 0;
	}

	public function getQuantityDone() : ?float
	{
		return 0;
	}

	public function getTimingFather() : ?HasTimingInterface
	{
		return null;
	}

	public function getProcessings() : Collection
	{
		return collect();
	}

	public function getPossibleSellablesByType(string $type) : array
	{
		$types = $this->getOrderrowsPossibleSellableTypes();

		$type = strtolower($type);

		return $types[$type]();
	}

	public function getFilteredByClientUrl()
	{
		return IbRouter::route(app('products'), 'orders.active.byClient', ['client' => $this->getClientId()]);
	}

	public function setName(string $value, bool $save = false)
	{
		return $this->_customSetter('name', $value, $save);
	}

	public function setClientId(string $value, bool $save = false)
	{
		$this->_customSetter('client_id', $value, $save);
	}

	public function checkCompletion()
	{
		if ($this->orderProducts()->notCompleted()->count() > 0)
			return $this->uncomplete();

		return $this->complete();
	}

	public function getDate() : ?Carbon
	{
		return $this->date;
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::gpc());
	}

	public function hasQuotation()
	{
		return ! ! $this->quotation_id;
	}

	public function getAddOrderrowByTypeUrl(string $type, bool $table = false) : string
	{
		return $this->getKeyedRoute('addOrderrow', [
			'order' => $this->getKey(),
			'type' => $type,
			'table' => $table
		]);
	}

	public function orderrows()
	{
		return $this->hasMany(Orderrow::gpc());
	}

	public function getOrderrows() : Collection
	{
		return $this->orderrows;
	}

	public function isFrozen() : bool
	{
		return ! ! $this->frozen;
	}

	public function getFreezeUrl() : string
	{
		return $this->getKeyedRoute('freeze');
	}

	public function getResetRowsIndexesUrl() : string
	{
		return $this->getKeyedRoute('resetRowsIndexes');
	}

	public function getAttachClientOperatorsToOrderrowsUrl()
	{
		return $this->getKeyedRoute('attachClientOperatorsToOrderrows');		
	}

	public function getFreezeButton() : ?Button
	{
		if ($this->isFrozen())
			return null;

		return Button::create([
			'href' => $this->getFreezeUrl(),
			'text' => 'products::orders.freeze',
			'icon' => 'lock'
		]);
	}

	public function getResetRowsIndexesButton() : Button
	{
		return Button::create([
			'href' => $this->getResetRowsIndexesUrl(),
			'text' => 'products::orders.resetRowsIndex',
			'icon' => 'sort'
		]);
	}

	public function getAttachClientOperatorsToOrderrowsButton() : Button
	{
		return Button::create([
			'href' => $this->getAttachClientOperatorsToOrderrowsUrl(),
			'text' => 'products::orders.attachClientOperatorsToOrderrows',
			'icon' => 'rotate'
		]);
	}

	/****
	 * DELIVERIES SECTION
	 *
	 *
	 *
	 *
	 *
	 */

	public function getDeliveriesPopup()
	{
		return app('warehouse')->route('deliveries.orders.popup', ['order' => $this->getKey()]);
	}

	public function getDeliveringChildren() : Collection
	{
		$collect = $this->getOrderProducts();

		return $collect->merge($this->getOrderrows());
	}

	public function getDeliveriesNamesArray() : array
	{
		$result = [];

		foreach ($this->getDeliveringChildren() as $child)
			$result = array_merge($child->getDeliveriesNamesArray());

		return array_unique($result);
	}

	public function getDeliveriesNamesStringAttribute() : ?string
	{
		if (! $result = $this->getDeliveriesNamesArray())
			return null;

		return implode(", ", $result);
	}

	public function getDeliveries() : ?Collection
	{
		return $this->deliveries;
	}

	private function bindDataFromLastOrderProduct()
	{
		if (! $lastOrderProduct = $this->orderProducts()->completed()->orderBy('completed_at', 'DESC')->first())
			throw new Exception('Ultimo componente non trovato per commessa ' . $this->getName() . ' <a href="' . $this->getOldEditUrl() . '">Controlla qui</a>');
	}

	private function uncomplete()
	{
		$this->setCompletedAt(null);

		$this->setLoadedAt(null);
		$this->save();
	}

	private function complete()
	{
		$this->bindDataFromLastOrderProduct();
		$this->save();
	}
}

