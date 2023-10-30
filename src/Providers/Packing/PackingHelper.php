<?php

namespace IlBronza\Products\Providers\Packing;

use Carbon\Carbon;
use IlBronza\Products\Models\Packing;
use IlBronza\Products\Models\Size;

class PackingHelper
{
	public $packing;
	public $D;

	public function __construct(Packing $packing)
	{
		$this->D = 40;
		$this->setPacking($packing);
	}

	public function getBasello()
	{
		return $this->D;
	}

	public function setPacking(Packing $packing)
	{
		$this->packing = $packing;
	}

	public function getProduct()
	{
		return $this->getPacking()->getProduct();
	}

	public function getPacking() : Packing
	{
		return $this->packing;
	}

	public function setSize(Size $size)
	{
		$this->size = $size;
	}

	public function getSize() : Size
	{
		return $this->size;
	}

	public function calculatePacking()
	{
		$this->getPacking()->setQuantityPerPackage(
			$this->getQuantityPerPackage()
		);

		$this->getPacking()->setPackageHeight(
			$this->getPackageHeight()
		);

		$this->getPacking()->setPackageLength(
			$this->getPackageLength()
		);

		$this->getPacking()->setPackageWidth(
			$this->getPackageWidth()
		);

		$this->getPacking()->setPackageWeight(
			$this->getPackageWeight()
		);

		$this->getPacking()->setPackageVolumeMq(
			$this->getPackageVolumeMq()
		);

		$this->getPacking()->setPackagePerLayer(
			$this->getPackagePerLayer()
		);

		$this->getPacking()->setLayersPerPacking(
			$this->getLayersPerPacking()
		);

		$this->getPacking()->setQuantityPerPacking(
			$this->getQuantityPerPacking()
		);

		$packingSizes = $this->getPackingSizes();

		$this->getPacking()->setPackingLength(
			$packingSizes['length']
		);
		$this->getPacking()->setPackingWidth(
			$packingSizes['width']
		);
		$this->getPacking()->setPackingHeight(
			$packingSizes['height']
		);

		$this->getPacking()->setPackingVolumeMq(
			$packingSizes['length'] *
			$packingSizes['width'] * 
			$packingSizes['height'] / 
			1000000000
		);

		$this->getPacking()->setPackingWeight(
			$this->getPackingWeight()
		);

		$this->getPacking()->setCalculatedAt(Carbon::now());
	}































	public function getMaxPackingHeight()
	{
		if($result = $this->getProduct()?->getMaxPackingHeight())
			return $result;

		return 1300;
	}

	public function getMaxPackingWidth()
	{
		if($result = $this->getProduct()?->getMaxPackingWidth())
			return $result;

		return 1700;
	}

	public function getMaxPackingLength()
	{
		if($result = $this->getProduct()?->getMaxPackingLength())
			return $result;

		return 1400;
	}












	public function setBasePackingSizes()
	{
		$maxPackingWidth = $this->getMaxPackingWidth();
		$maxPackingLength = $this->getMaxPackingLength();

		$packageWidth = $this->getPacking()->getPackageWidth();
		$packageLength = $this->getPacking()->getPackageLength();

		if(! $packageLength)
			throw new \Exception('Lunghezza pacco non valida');

		if(! $packageWidth)
			throw new \Exception('Larghezza pacco non valida');

		if($packageWidth < $packageLength)
		{
			$paccoCorto = $packageWidth;
			$paccoLungo = $packageLength;
		}
		else
		{
			$paccoLungo = $packageWidth;
			$paccoCorto = $packageLength;
		}

		if($maxPackingWidth < $maxPackingLength)
		{
			$bancaleCorto = $maxPackingWidth;
			$bancaleLungo = $maxPackingLength;
		}
		else
		{
			$bancaleLungo = $maxPackingWidth;
			$bancaleCorto = $maxPackingLength;
		}

		return [
			'paccoCorto' => $paccoCorto,
			'paccoLungo' => $paccoLungo,
			'bancaleCorto' => $bancaleCorto,
			'bancaleLungo' => $bancaleLungo
		];
	}

	public function getPackingSizes()
	{
		extract($this->setBasePackingSizes());

		$result = [
			'height' => $this->getPacking()->getLayersPerPacking() * $this->getPacking()->getPackageHeight()
		];

		$divisioneLunghi = $bancaleLungo / $paccoLungo;
		$divisioneiCorti = $bancaleCorto / $paccoCorto;

		$this->pacchiPerLungo = (($val = floor($divisioneLunghi)) == 0) ? 1 : $val;
		$this->pacchiPerCorto = (($val = floor($divisioneiCorti)) == 0) ? 1 : $val;

		if($this->pacchiPerLungo * $this->pacchiPerCorto == 1)
		{
			$result['length'] = $this->pacchiPerLungo * $paccoLungo;
			$result['width'] = $this->pacchiPerCorto * $paccoCorto;

			return $result;
		}

		$imballoLungo = $pacchiPerLungo * $paccoLungo;
		$imballoCorto = $pacchiPerCorto * $paccoCorto;

		$deltaL = floor(($divisioneLunghi - $pacchiPerLungo) * $packageLength);

		mori($deltaL);

		$xcl = $deltaL / $packageWidth;
		$xlc = $maxPackingLength / $packageLength;

		$ppcL = floor($xcl);
		$pplC = floor($xlc);

		$otherWidth = $pplC * $packageLength;

		$palletLength += $ppcL * $packageWidth;

		if($otherWidth > $palletWidth)
			$palletWidth = $otherWidth;

		return [
			'length' => $palletLength,
			'width' => $palletWidth,
			'height' => $this->getPacking()->getLayersPerPacking() * $this->getPacking()->getPackageHeight()
		];
	}





	public function getQuantityPerPacking()
	{
		$piecesPerPacking = $this->getPacking()->getQuantityPerPackage();
		$layersNumber = $this->getPacking()->getLayersPerPacking();
		$packagingPerLayer = $this->getPacking()->getPackagePerLayer();

		return $piecesPerPacking * $packagingPerLayer * $layersNumber;
	}

	public function getLayersPerPacking() : int
	{
		$palletMaxHeight = $this->getMaxPackingHeight();

		$packageHeight = $this->getPacking()->getPackageHeight();

		$result = floor($palletMaxHeight / $packageHeight);

		if($result == 0)
			return 1;

		if($this->getPacking()->getQuantityPerPackage() == 1)
			return $result;

		if($result > 9)
			return 9;

		return $result;
	}

	public function getPackagePerLayer()
	{
		$BL = $this->getMaxPackingWidth();
		$BC = $this->getMaxPackingLength();

		$packageWidth = $this->getPacking()->getPackageWidth();
		$packageLength = $this->getPacking()->getPackageLength();

		if($packageLength > $packageWidth)
		{
			$PL = $packageLength;
			$PC = $packageWidth;
		}
		else
		{
			$PC = $packageLength;
			$PL = $packageWidth;
		}

		if(! $PL)
			throw new \Exception('Lunghezza pacco non valida');

		if(! $PC)
			throw new \Exception('Larghezza pacco non valida');

		$xL = $BL / $PL;
		$xC = $BC / $PC;

		$ppl = floor($xL);
		$ppc = floor($xC);

		if(($ppl == 0)&&($ppc == 0))
			return 1;

		if(($ppl == 0))
			$ppl = 1;

		if(($ppc == 0))
			$ppc = 1;

		// else return 'controllare qua';

		$deltaL = floor(($xL - $ppl) * $PL);

		$xcl = $deltaL / $PC;
		$xlc = $BC / $PL;

		$ppcL = floor($xcl);
		$pplC = floor($xlc);

		// mori($pplC);

		$pps = $ppc * $ppl + $ppcL * $pplC;

		return $pps;
	}

	public function getPackageVolumeMq()
	{
		return 	round($this->getPackageHeight() *
				$this->getPackageLength() *
				$this->getPackageWidth() /
				1000000000, 3);
	}

	public function getPackageWeight() : float
	{
		return $this->getPacking()->getQuantityPerPackage() * $this->getSize()->getPieceWeightGrams() / 1000;
	}

	public function getPackagesPerPacking() : int
	{
		return $this->getLayersPerPacking() * $this->getPackagePerLayer();
	}

	public function getPackingWeight() : float
	{
		return $this->getPackagesPerPacking() * $this->getPackageWeight();
	}

	public function getPackageWidth()
	{
		if($this->getSize()->isStretched())
		{
			$papers = $this->getSize()->getPapersNumber();

			if($papers == 4)
				throw new \Exception ('considerare i 4 fogli nelle misure');

			return $this->getSize()->getStretchedOutLength() * $papers - ($this->getBasello()  * $papers) + $this->getBasello();
		}

		return (($this->getSize()->getStretchedOutLength() - $this->getBasello()) / 2) + 7;
	}

	public function getPackageLength() : float
	{
		return $this->getSize()->getStretchedOutHeight();		
	}

	public function getQuantityPerPackage()
	{
		if($this->getSize()->isStretched())
		{
			if($this->getSize()->getStencil()->requiresPlatina())
				return match ($this->getSize()->getWaveString())
				{
					'C' => 30,
					'EB' => 30,
					'BC' => 20,
					'B' => 50,
					'EE' => 50,
					'E' => 100,
					default => 50
				};

			if($this->getSize()->stretched_out_height < 1400)
			{
				if($this->getSize()->stretched_out_width < 1600)
					return match ($this->getSize()->getWaveString())
					{
						'EB' => 30,
						'C' => 30,
						'BC' => 20,
						'E' => 50,
						'EE' => 40,
						'B' => 40,
						default => 20
					};

				return match ($this->getSize()->getWaveString())
				{
					// 'BC' => 180,
					// 'EB, EE, C' => 270,
					// 'B' => 360,
					// default => 180
					default => 1
				};
			}

			if($this->getSize()->stretched_out_width < 1400)
			{
				if($this->getSize()->stretched_out_height < 1600)
					return match ($this->getSize()->getWaveString())
					{
						'EB' => 30,
						'C' => 30,
						'BC' => 20,
						'E' => 50,
						'EE' => 40,
						'B' => 40,
						default => 20
					};

				return match ($this->getSize()->getWaveString())
				{
					// 'BC' => 180,
					// 'EB, EE, C' => 270,
					// 'B' => 360,
					// default => 180
					default => 1
				};
			}

			if($this->getSize()->stretched_out_height < 1560)
				if($this->getSize()->stretched_out_width < 1400)
					return match ($this->getSize()->getWaveString())
					{
						'BC' => 10,
						'EB' => 15,
						'EE' => 15,
						'C' => 15,
						'B' => 20,
						default => 10
					};

			return match ($this->getSize()->getWaveString())
			{
				// 'BC' => 180,
				// 'EB, EE, C' => 270,
				// 'B' => 360,
				// default => 180
				default => 1
			};
		}

		if($this->getSize()->double_manual_glueing)
			return match ($this->getSize()->getWaveString())
			{
				// 'BC' => 90,
				// 'EB, EE, C' => 135,
				// 'B' => 180,
				// default => 90
				default => 1
			};

		if($this->getSize()->double_manual_sewing)
			return match ($this->getSize()->getWaveString())
			{
				// 'BC' => 90,
				// 'EB, EE, C' => 135,
				// 'B' => 180,
				// default => 90
				default => 1
			};

		if($this->getSize()->getStencil()->requiresPlatina())
			return match ($this->getSize()->getWaveString())
			{
				'C' => 15,
				'EB' => 15,
				'BC' => 10,
				'E' => 25,
				'B' => 20,
				'EE' => 20,
				default => 25
			};

		if($this->getSize()->getWaveString() == 'BC')
		{
			if($this->getSize()->piece_weight_grams < 2100)
				return 10;

			return 5;
		}

		return match ($this->getSize()->getWaveString())
		{
			'EB' => 15,
			'C' => 15,
			'BC' => 10,
			'E' => 25,
			'B' => 20,
			'EE' => 20,
			default => 10
		};
	}

	public function getPackageHeight() : float
	{
		$result = $this->getSize()->getProduct()->getWaveWidth() * $this->getPacking()->getQuantityPerPackage();

		if($this->getSize()->isStretched())
			return $result;

		return $result * 2;
	}




}