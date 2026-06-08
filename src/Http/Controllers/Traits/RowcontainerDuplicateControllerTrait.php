<?php

namespace IlBronza\Products\Http\Controllers\Traits;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\QuotationOrder\RowcontainerDuplicateRelationsPresenterHelper;
use Illuminate\Http\Request;

trait RowcontainerDuplicateControllerTrait
{
	abstract protected function getRowcontainerModelConfigPrefix() : string;

	protected function renderSelectRelationsView(
		ProductPackageBaseRowcontainerModel $rowContainer,
		?string $eventStartsAt,
		string $duplicateAction
	)
	{
		$prefix = $this->getRowcontainerModelConfigPrefix();

		$presenter = new RowcontainerDuplicateRelationsPresenterHelper($rowContainer);

		return view('products::rowcontainers.duplicate.selectRelations', [
			'rowContainer' => $rowContainer,
			'eventStartsAt' => $eventStartsAt,
			'relationBlocks' => $presenter->getRelationsBlocks(),
			'duplicateAction' => $duplicateAction,
			'pageTitle' => trans('products::' . $prefix . 's.duplicateSelectRelations', [
				$prefix => $rowContainer->getName(),
			]),
			'confirmLabel' => trans('products::' . $prefix . 's.duplicateConfirm'),
			'emptyRelationsMessage' => trans('products::' . $prefix . 's.duplicateRelationsEmpty'),
		]);
	}

	protected function validateDuplicateDateRequest(Request $request) : array
	{
		return $request->validate([
			'event_starts_at' => 'date|nullable'
		]);
	}

	protected function validateDuplicateRequest(Request $request) : array
	{
		return $request->validate([
			'event_starts_at' => 'date|nullable',
			'relations' => 'array',
			'relations.*.enabled' => 'nullable',
			'relations.*.instances' => 'array',
			'relations.*.instances.*' => 'string',
		]);
	}
}
