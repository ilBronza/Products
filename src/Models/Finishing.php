<?php

namespace IlBronza\Products\Models;

class Finishing extends ProductPackageBaseModel
{
	static $modelConfigPrefix = 'finishing';

	static function getDefault()
	{
		if($result = static::where('name', 'default')->first())
			return $result;

		$finishing = static::make();
		$finishing->name = 'default';
		$finishing->save();

		return $finishing;
	}

}