<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\FormField\FormField;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

abstract class AssociateOrCreateParentRowByTypeIndexByTableController extends SupplierCRUD
{
	use CRUDIndexTrait;

	public $avoidCreateButton = true;
	public $rowSelectCheckboxes = false;
	public bool|string $caption = false;
	public ProductPackageBaseRowcontainerModel $rowcontainer;
	public string $type;

	public $allowedMethods = ['index', 'store'];

	abstract protected function getRowcontainerModelClass() : string;
	abstract protected function getRowModelClass() : string;
	abstract protected function getRowContainer($row) : ? ProductPackageBaseRowcontainerModel;

	public function index(Request $request, $rowcontainer, string $type)
	{
		$this->type = $type;
		$this->rowcontainer = $this->getRowcontainerModelClass()::gpc()::findOrFail($rowcontainer);

		app('uikittemplate')->addBodyHtmlClass("sellabletype-{$type}");

		return $this->_index($request);
	}

	public function getPossibleSellableSupplierArray() : array
	{
		return Sellable::gpc()::notArchived()->orderBy('name')->byType($this->type)->pluck('name', 'id')->toArray();
	}

	public function getParentSellable() : Sellable
	{
		return Sellable::gpc()::notArchived()->byName(
			cconfig('products.models.sellable.parentRowNames.parentRowFor' . $this->type . 'Name')
		)->first();
	}

	public function addPostFieldsToTable()
	{
		foreach(request()->ids as $id)
			$this->getTable()->addPostField(FormField::createFromArray([
				'type' => 'text',
				'name' => 'ids[]',
				'value' => $id
			]));

		$this->getTable()->addPostField(FormField::createFromArray([
			'label' => trans('products::types.' . $this->type),
			'type' => 'text',
			'name' => 'sellable_id',
			'value' => $this->getParentSellable()->getKey(),
		]));
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

		return $this->getModelClass()::query()
			->whereHas('sellables', fn ($query) => $query->byType($this->type))
			->with('target.address')
			->get();
	}

	public function getIndexFieldsArray() : array
	{
		$containerPrefix = $this->rowcontainer->getModelConfigPrefix();

		return [
			'translationPrefix' => 'products::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'target.name' => 'flat',
				'mySelfAssociateOrCreateParentRowByType' => "products::{$containerPrefix}rows.associateOrCreateParentRowByType",
				'mySelfTargetClass.target' => 'models.classBasename',
			],
		];
	}

	protected function associateOrCreateParentRowByType(Request $request, ProductPackageBaseRowcontainerModel $container, string $supplier)
	{
		$rows = $this->getSelectedRows($request, $container);

		$supplier = Supplier::gpc()::findOrFail($supplier);

		$sellable = Sellable::gpc()::find($request->input('sellable_id'));

		$result = DB::transaction(function () use ($container, $supplier, $sellable, $rows)
		{
			$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);
			$result = RowAssociatorHelper::associateRowBySellableSupplier($container, $sellableSupplier);

			foreach($rows as $row)
				$row->associateParent($result->row);

			return $result;
		});

		return view('datatables::utilities.closeIframe', [
			'tablesToRefresh' => array_unique(
				array_merge(
					$rows->first()->getTablesToRefresh(),
					$result->row->getTablesToRefresh()
				)
			)
		]);
	}

	protected function getSelectedRows(Request $request, ProductPackageBaseRowcontainerModel $container)
	{
		$validRowIds = $container->rows()->select('id')->whereIn('id', $request->ids)->pluck('id');

		return RowsFinderHelper::getCompositeRowCollectionByIds($validRowIds);

	}
}
