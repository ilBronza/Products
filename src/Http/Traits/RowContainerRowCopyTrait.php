<?php

namespace IlBronza\Products\Http\Traits;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\ProductPackageBaseRowModel;

trait RowContainerRowCopyTrait
{
	public $allowedMethods = ['replicateLastRowByType'];

	public function closeIframe(array $tablesToRefresh = [])
	{
		return view('datatables::utilities.closeIframe', compact('tablesToRefresh'));
	}

	public function getTableToRefresh($type)
	{
		if ($type == 'Hotel')
			return ['hotelRows'];

		//				else if ($type == '')
		//					$tablesToRefresh = ['operatorRows'];
		//
		//				else if ($type == '')
		//					$tablesToRefresh = ['vehicleRows'];
		//
		//				else if ($type == '')
		//					$tablesToRefresh = ['surveillanceRows'];
		//
		//		else if ($type == '')
		//			$tablesToRefresh = ['rentRows'];
		//
		//		else if ($type == '')
		//			$tablesToRefresh = ['controlRoomRows'];
		//
		//		else if ($type == '')
		//			$tablesToRefresh = ['reimbursementRows'];

		dd('gestire gli altri tipi: ' . $type);
	}

	public function getLastRowByType(ProductPackageBaseRowcontainerModel $rowContainer, string $type) : ? ProductPackageBaseRowModel
	{
		return $rowContainer->rows()->bySellableType($type)->orderByDesc('sorting_index')->first();
	}

	public function _replicateLastRowByType(ProductPackageBaseRowcontainerModel $rowContainer, string $type)
	{
		if(! $row = $this->getLastRowByType($rowContainer, $type))
			return $this->closeIframe();

		$newRow = $row->replicate();
		$newRow->save();

		$newExtrafields = $row->extraFields->replicate();
		$newRow->extraFields()->save($newExtrafields);

		$newRow->sorting_index = $row->sorting_index + 1;
		$newRow->save();

		$tableToRefresh = $this->getTableToRefresh($type);

		return $this->closeIframe($tableToRefresh);
	}
}