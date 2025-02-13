<?php

namespace IlBronza\Products\Http\Controllers\Sellable;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;

use function dd;
use function ff;

class SellableCreateStoreController extends SellableCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDRelationshipTrait;

	public ? SellableItemInterface $target = null;

    public $allowedMethods = [
        'create',
        'store',
    ];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.sellable.parametersFiles.create');
    }

	public function performAdditionalOperations()
	{
		if(! $helperClass = config('products.models.sellable.helpers.targetCreator.' . $this->getModel()->getType()))
			throw new \Exception('configurare config(\'products.models.sellable.helpers.targetCreator.' . $this->getModel()->getType() . '\')');

		$this->target = (new $helperClass($this->getModel()))->provideTarget();

		$this->getModel()->target()->associate($this->target);
		$this->getModel()->save();
	}

	public function getTarget() : ? SellableItemInterface
	{
		return $this->target;
	}

	public function getAfterStoredRedirectUrl() {
		if($target = $this->getTarget())
			return $target->getEditUrl();

		if($url = $this->getReturnUrl())
			return $url;

		if($this->isSaveAndNew())
			return $this->getCreateUrl();

		if($this->isSaveAndRefresh())
			return $this->getRouteUrlByType('edit');

		return $this->getRouteUrlByType('index');
	}
}
