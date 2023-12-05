<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\Order\OrderRelationshipsTrait;
use IlBronza\Products\Models\Traits\Order\OrderScopesTrait;
use Illuminate\Support\Collection;

class Order extends ProductPackageBaseModel
{
	use CRUDParentingTrait;
	use CRUDSluggableTrait;

	use OrderRelationshipsTrait;
	use OrderScopesTrait;

	use CompletionScopesTrait;

	static $modelConfigPrefix = 'order';

	static $deletingRelationships = ['orderProducts'];

	public function scopeByClientId($query, string $clientId)
	{
		$query->where(static::getProjectClassName()::make()->getTable() . '.client_id', $clientId);
	}

	public function scopeByClientIds($query, array|Collection $clientsIds)
	{
		$query->whereIn('client_id', $clientsIds);
	}

	public function scopeByClientsIds($query, array|Collection $clientsIds)
	{
		$query->whereIn('client_id', $clientsIds);
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

}