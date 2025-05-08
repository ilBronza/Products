<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Sellables\Sellable;

use function dd;
use function explode;
use function json_encode;
use function strpos;

class SellablePriceCreatorHelper
{
	public Sellable $sellable;
	public SellableItemInterface $target;

	public function __construct(Sellable $sellable)
	{
		$this->setSellable($sellable);
		$this->setSellableTarget();
	}

	public function setSellableTarget() : void
	{
		$this->target = $this->sellable->target;
	}

	public function getSellableTarget() : SellableItemInterface
	{
		return $this->target;
	}

	public function setSellable(Sellable $sellable) : void
	{
		$this->sellable = $sellable;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function getTargetPrices() : array
	{
		return $this->getSellableTarget()->getPriceFieldsForSellable();
	}

	public function setPricesFromTarget()
	{
		$target = $this->getSellableTarget();
		$prices = $this->getTargetPrices();

		$sellable = $this->getSellable();

		$castables = [];

		if(method_exists($target, 'getExtraFieldsCasts'))
			$castables = $target->getExtraFieldsCasts();

		foreach($prices as $priceName)
		{
			if(strpos(($castables[$priceName] ?? null), 'CastFieldPrice') !== false)
			{
				$pieces = explode(':', $castables[$priceName]);

				$names = array_pop($pieces);

				$_names = explode(',', $names);

				$collectionId = array_shift($_names);

				$price = $target->providePriceByCollectionId($collectionId);

				$sellable->setPriceByCollectionId(
					$collectionId,
					$target->$priceName
				);
			}
		}

		$sellable->save();
	}
}