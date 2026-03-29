<?php

namespace IlBronza\Products\Models\PackagingModels;

use IlBronza\Products\Models\Product\Product as IbProduct;

class Product extends IbProduct
{
	private function setSizeAndReturn(string $field, $value)
	{
		$value = floor($value);

		$size = $this->getSize();

		// $size->neat_sheet_length = $value;
		// $size->save();

		return $value;
	}

	public function getNeatSheetLengthAttribute()
	{
		if(! $size = $this->getSize())
			return null;

		if($value = $size->neat_sheet_length)
			return $value;

		$strings = json_decode($size->horizontal_extra_strings ?? '[]');

		if(! count($strings))
		{
			if(! $value = $this->extraFields->width)
				return null;

			// $size->neat_sheet_length = $value;
			// $size->save();

			return $value;
		}

		$value = 0;

		foreach($strings as $string)
			$value += $string->distance ?? 0;

		return $this->setSizeAndReturn('neat_sheet_length', $value);
		// $size->neat_sheet_length = $value;
		// $size->save();

		// return $value;
	}

	public function getNeatSheetLength()
	{
		return $this->neat_sheet_length;
	}

	public function getNeatSheetWidthAttribute()
	{
		if(! $size = $this->getSize())
			return null;

		if($value = $size->neat_sheet_width)
			return $value;

		if($value = floor($this->gross_height))
			return $this->setSizeAndReturn('neat_sheet_width', $value);

		if($value = $this->extraFields->height)
			return $this->setSizeAndReturn('neat_sheet_width', $value);

		return null;
	}

	public function getNeatSheetWidth()
	{
		return $this->neat_sheet_width;
	}

	public function getGrossSheetLength()
	{
		return $this->gross_sheet_length;
	}

	public function getGrossSheetWidth()
	{
		return $this->gross_sheet_width;
	}

	public function getGrossSheetAreaAttribute() : float
	{
		return round($this->getGrossSheetLength() * $this->getGrossSheetWidth() / 1000000, 3);
	}

	public function getGrossSheetArea()
	{
		return $this->gross_sheet_area;
	}

	public function getNeatPaperSizesString() : string
	{
		return "{$this->getNeatSheetWidth()}x{$this->getNeatSheetLength()}";
	}

	public function getGrossPaperSizesString() : string
	{
		return "{$this->getGrossSheetWidth()}x{$this->getGrossSheetLength()}";
	}


	public function getGrossSheetLengthAttribute()
	{
		if(! $size = $this->getSize())
			return null;

		if($value = $size->sheet_length)
			return $value;

		if($value = $size->gross_length)
			return $this->setSizeAndReturn('sheet_length', $value);

		if($value = $this->extraFields->width)
			return $this->setSizeAndReturn('sheet_length', $value);

		return null;
	}

	public function getGrossSheetWidthAttribute()
	{
		if(! $size = $this->getSize())
			return null;

		if($value = $size->sheet_width)
			return $value;

		if($value = floor($size->gross_height))
			return $this->setSizeAndReturn('sheet_length', $value);

		if($value = $this->extraFields->height)
			return $this->setSizeAndReturn('sheet_length', $value);

		return null;
	}
}