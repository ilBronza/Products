<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\Sellable\SellableCRUD;

use IlBronza\Products\Models\Quotations\Quotation;
use Illuminate\Http\Request;

class QuotationAddQuotationrowIndexByTableController extends SellableCRUD
{
	public $avoidCreateButton = true;
	public $rowSelectCheckboxes = true;

	use CRUDIndexTrait;

	public Quotation $quotation;
	public string $type;

	public $allowedMethods = ['index'];

	public function getIndexElements()
	{
		return $this->getModelClass()::byType($this->type)->with(
			'target',
			'suppliers'
		)
		 ->get();
	}

	public function addIndexButtons()
	{
		$this->getTable()->createPostButton([
			'href' => app('products')->route('quotations.addQuotationrow', ['quotation' => $this->quotation->getKey(), 'type' => $this->type]),
			'text' => 'buttons.addRows',
			'icon' => 'plus'
		]);
	}

	public function index(Request $request, $quotation, $type)
	{
		$this->type = $type;
		$this->quotation = Quotation::gpc()::find($quotation);

		return $this->_index($request);
	}

	public function getIndexFieldsArray()
	{
		return [
			'translationPrefix' => 'products::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'name' => 'flat',
				'suppliers' => 'relations.belongsToMany',
			]
		];
	}
}
