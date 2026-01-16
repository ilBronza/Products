<?php

namespace IlBronza\Products\Providers\DatatablesFields\Workstations;

use IlBronza\Datatables\DatatablesFields\DatatableFieldFlatColor;

class DatatableFieldLabel extends DatatableFieldFlatColor
{
	public ? string $translationPrefix = 'products::fields';
	public ? string $forcedStandardName = 'workstationLabel';

	public function transformValue($value)
	{
		if(class_basename($value) != 'Workstation')
			$workstation = $value->getWorkstation();
		else
			$workstation = $value;

		if(! $workstation)
			return [
				null,
				null
			];

		return [
			$workstation->alias,
			$workstation->color
		];
	}
}