<?php

namespace IlBronza\Products\Models\Quotations;

use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Clients\Models\Client;
use IlBronza\Clients\Models\Traits\InteractsWithClientsTrait;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Quotations\Quotation;

class Project extends ProductPackageBaseModel
{
	use InteractsWithClientsTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;

	static $modelConfigPrefix = 'project';
	static $deletingRelationships = [];

	protected $casts = [
		'started_at' => 'date',
		'completed_at' => 'date',
	];

	public function quotations()
	{
		return $this->hasMany(Quotation::gpc());
	}

	public function orders()
	{
		return $this->hasMany(Order::gpc());
	}

	public function client()
	{
		return $this->belongsTo(Client::gpc());
	}

	public function scopeByClient($query, string|Client $client)
	{
		if(! is_string($client))
			$client = $client->getKey();

		return $query->where('client_id', $client);
	}

	public function getReorderProjectsUrl() : string
	{
		return $this->getKeyedRoute('reorder.byClient', ['client' => $this->client_id]);
	}
}