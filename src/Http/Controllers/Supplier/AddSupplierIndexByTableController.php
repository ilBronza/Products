<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\FormField\FormField;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Http\Request;

abstract class AddSupplierIndexByTableController extends SupplierCRUD
{
	use CRUDIndexTrait;

	public $avoidCreateButton = true;
	public $rowSelectCheckboxes = false;
	public bool|string $caption = false;

	public ProductPackageBaseRowcontainerModel $rowcontainer;
	public string $type;

	public $allowedMethods = ['index'];

	abstract protected function getRowcontainerModelClass() : string;

	public function getPossibleSellableSupplierArray() : array
	{
		return Sellable::gpc()::notArchived()->orderBy('name')->byType($this->type)->select('name', 'id')->pluck('name', 'id')->toArray();
	}

	public function addPostFieldsToTable()
	{
		$generic = Sellable::gpc()::provideGenericByType($this->type);

		$possibleSellablesArray = $this->getPossibleSellableSupplierArray();

		$this->getTable()->addPostField(
			FormField::createFromArray([
				'label' => trans('products::types.' . $this->type),
				'type' => 'select',
				'multiple' => false,
				'name' => 'sellable_id',
				'value' => $generic->getKey(),
				'list' => $possibleSellablesArray
			])
		);
	}

	public function getIndexElements()
	{
		$rowcontainerModelClass = $this->getRowcontainerModelClass();
		$getterMethod = "get{$this->type}Suppliers";

		if(method_exists($rowcontainerModelClass::gpc(), $getterMethod))
		{
			app('uikittemplate')->addCustomGetter($getterMethod);

			return $rowcontainerModelClass::gpc()::$getterMethod();
		}

		$result = $this->getModelClass()::query()
			->whereHas('sellables', function($query)
			{
				$query->byType($this->type);
			})
			->with([
				'target.address',
			])
			->get();

		return $result;
	}

	public function index(Request $request, $rowcontainer, $type)
	{
		$this->type = $type;
		$this->rowcontainer = $this->getRowcontainerModelClass()::gpc()::find($rowcontainer);

		app('uikittemplate')->addBodyHtmlClass("sellabletype-{$type}");

		return $this->_index($request);
	}

	public function getRowcontainerModel()
	{
		return $this->rowcontainer;
	}

	public function getIndexFieldsArray()
	{
		$type = lcfirst($this->type);

		if(! $file = config("products.models.supplier.fieldsGroupsFiles.pick.{$type}"))
			$file = cconfig("products.models.supplier.fieldsGroupsFiles.pick.index");

		return $file::getTracedFieldsGroup(
			$this->getRowcontainerModel()
		);
	}
}
