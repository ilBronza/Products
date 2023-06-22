<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\Order\OrderRelationshipsTrait;
use Illuminate\Support\Collection;

class Order extends ProductPackageBaseModel
{
	use CRUDParentingTrait;
	use OrderRelationshipsTrait;
	use CRUDSluggableTrait;

	use CompletionScopesTrait;

	static $modelConfigPrefix = 'order';

	static $deletingRelationships = ['orderProducts'];

	public function scopeByClientId($query, string $clientId)
	{
		$query->where('client_id', $clientId);
	}

	public function scopeByClientIds($query, array|Collection $clientsIds)
	{
		$query->whereIn('client_id', $clientsIds);
	}

	public function scopeByClientsIds($query, array|Collection $clientsIds)
	{
		$query->whereIn('client_id', $clientsIds);
	}
}