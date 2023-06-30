<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
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

	public function setName(string $name, bool $save = false)
	{
		return $this->_customSetter('name', $name, $save);
	}
}