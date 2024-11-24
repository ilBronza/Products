<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Traits\Assignee\ProductAssignmentTrait;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\Order\CommonOrderQuotationTrait;
use IlBronza\Products\Models\Traits\Order\OrderRelationshipsTrait;
use IlBronza\Products\Models\Traits\Order\OrderScopesTrait;
use Illuminate\Support\Collection;

class Order extends ProductPackageBaseRowcontainerModel
{
	use CommonOrderQuotationTrait;

	use CRUDSluggableTrait;

	use OrderRelationshipsTrait;
	use OrderScopesTrait;

	use ProductAssignmentTrait;
	use CompletionScopesTrait;

	static $modelConfigPrefix = 'order';
	public $classnameAbbreviation = 'o';

	static $deletingRelationships = ['orderProducts'];

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
		if($this->orderProducts()->notCompleted()->count() > 0)
			return $this->uncomplete();

		return $this->complete();
	}

	private function bindDataFromLastOrderProduct()
	{
		if(! $lastOrderProduct = $this->orderProducts()->completed()->orderBy('completed_at', 'DESC')->first())
			throw new \Exception('Ultimo componente non trovato per commessa ' . $this->getName() . ' <a href="' . $this->getOldEditUrl() . '">Controlla qui</a>');
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

	public function getAddOrderrowByTypeUrl(string $type) : string
	{
		return $this->getKeyedRoute('addOrderrow', [
			'order' => $this->getKey(),
			'type' => $type
		]);
	}

	public function orderrows()
	{
		return $this->hasMany(Orderrow::gpc());
	}

}