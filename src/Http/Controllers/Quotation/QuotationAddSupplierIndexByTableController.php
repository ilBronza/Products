<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\FormField\FormField;
use IlBronza\Products\Http\Controllers\Supplier\SupplierCRUD;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Http\Request;

class QuotationAddSupplierIndexByTableController extends SupplierCRUD
{
	use CRUDIndexTrait;

	public $avoidCreateButton = true;
	public $rowSelectCheckboxes = false;
	public bool|string $caption = false;

	public Quotation $quotation;
	public string $type;

	public $allowedMethods = ['index'];


	public function addPostFieldsToTable()
	{
		$generic = Sellable::gpc()::provideGenericByType($this->type);

		$possibleSellablesArray = Sellable::gpc()::notArchived()->orderBy('name')->byType($this->type)->select('name', 'id')->pluck('name', 'id')->toArray();

		$this->getTable()->addPostField(
			FormField::createFromArray([
				'label' => 'Servizi/Materiali',
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
		$getterMethod = "get{$this->type}Suppliers";

		if(method_exists(Quotation::gpc(), $getterMethod))
		{
			app('uikittemplate')->addCustomGetter($getterMethod);

			return Quotation::gpc()::$getterMethod();
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

	public function index(Request $request, $quotation, $type)
	{
		$this->type = $type;
		$this->quotation = Quotation::gpc()::find($quotation);

		return $this->_index($request);
	}

	public function getRowcontainerModel()
	{
		return $this->quotation;
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
