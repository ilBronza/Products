<?php

namespace IlBronza\Products\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use function method_exists;
use function round;

trait CURSORImageTrait
{
	public function isPdfPrintable() : bool
	{
		return !! $this->pdf_quotation_show;
	}

	public function getPdfQuantity() : string
	{
		if($this->pdf_quotation_show_quantity)
			return $this->getQuantity();

		return '-';
	}

	public function getPdfSingleRevenue() : string
	{
		if($this->pdf_quotation_show_price)
			return $this->calculated_single_revenue;

		return '-';
	}

	public function getPdfTotalVatted() : string
	{
		if(! $this->pdf_quotation_show_price)
			return '-';
		
		return ((float) $this->calculated_total_row_revenue) * (((float) $this->calculated_vat / 100) + 1);
	}

	public function getPdfTotalRevenue() : string
	{
		if($this->pdf_quotation_show_price)
			return $this->calculated_total_row_revenue;

		return '-';		
	}

	public function getPdfVat() : string
	{
		if($this->pdf_quotation_show_price)
			return $this->calculated_vat . ' %';

		return '-';		
	}

	public function getPdfImagePath($target = null): ?string
	{
		if( ! $target)
			$target = method_exists($this, 'getSellable') ? $this->getSellable()?->getTarget() : null;

		if (! $target || ! method_exists($target, 'getFirstMedia')) {
			return null;
		}

		/** @var Media|null $media */
		$media = $target->getFirstMedia('default');

		if (! $media) {
			return null;
		}

		$path = $media->getPath();

		return (is_string($path) && $path !== '' && file_exists($path)) ? $path : null;
	}

	public function getPdfBySupplierImageSrc() : ? string
	{
		static $cache = [];

		$path = $this->getPdfImagePath(
			$this->getSupplier()?->getTarget()
		);

		if (! $path) {
			return null;
		}

		if (isset($cache[$path])) {
			return $cache[$path];
		}

		$mime = @mime_content_type($path) ?: 'image/jpeg';
		$cache[$path] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));

		return $cache[$path];	}

	/**
	 * Returns an <img src="..."> value suitable for dompdf.
	 * We use a data-URI to avoid dompdf quality issues with background-image.
	 */
	public function getPdfImageSrc(): ?string
	{
		static $cache = [];

		$path = $this->getPdfImagePath();

		if (! $path) {
			return null;
		}

		if (isset($cache[$path])) {
			return $cache[$path];
		}

		$mime = @mime_content_type($path) ?: 'image/jpeg';
		$cache[$path] = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));

		return $cache[$path];
	}

	public function getPdfImageThumbStyle(int $sizePx = 110): string
	{
		$sizePx = $sizePx > 0 ? $sizePx : 110;

		$path = $this->getPdfImagePath();
		$thumbStyle = 'width:' . $sizePx . 'px;height:' . $sizePx . 'px;';

		if (! $path) {
			return $thumbStyle;
		}

		$imageSize = @getimagesize($path) ?: null;

		if (! is_array($imageSize) || empty($imageSize[0]) || empty($imageSize[1])) {
			return $thumbStyle;
		}

		$w = (int) $imageSize[0];
		$h = (int) $imageSize[1];

		if ($w <= 0 || $h <= 0) {
			return $thumbStyle;
		}

		if ($w > $h) {
			// landscape: scale by height, crop left/right
			$scaledW = (int) round($sizePx * $w / $h);
			$ml = (int) round(-(max(0, $scaledW - $sizePx) / 2));
			return 'height:' . $sizePx . 'px;width:' . $scaledW . 'px;margin-left:' . $ml . 'px;';
		}

		if ($h > $w) {
			// portrait: scale by width, crop top/bottom
			$scaledH = (int) round($sizePx * $h / $w);
			$mt = (int) round(-(max(0, $scaledH - $sizePx) / 2));
			return 'width:' . $sizePx . 'px;height:' . $scaledH . 'px;margin-top:' . $mt . 'px;';
		}

		return $thumbStyle;
	}

}

