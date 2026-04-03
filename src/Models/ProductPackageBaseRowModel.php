<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Interfaces\TimelineInterfaces\TimelineGroupInterface;
use IlBronza\CRUD\Interfaces\TimelineInterfaces\TimelineItemInterface;
use IlBronza\CRUD\Traits\Model\CRUDTimeRangesTrait;
use IlBronza\CRUD\Traits\Timeline\IsTimelineItemTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\Orderrow\TypedOrderrowTrait;
use IlBronza\Timings\Interfaces\TimeIntervalInterface;
use IlBronza\Timings\Interfaces\TimelineInterface;
use IlBronza\Ukn\Ukn;
use Illuminate\Support\Collection;
use function class_basename;
use function get_class;
use function get_class_methods;
use function is_string;

class ProductPackageBaseRowModel extends ProductPackageBaseModel implements TimeIntervalInterface, TimelineItemInterface
{
	use InteractsWithPriceTrait;
	use IsTimelineItemTrait;
	use CRUDTimeRangesTrait;
	use TypedOrderrowTrait;

	protected $casts = [
		'starts_at' => 'date',
		'ends_at' => 'date',
	];

	static $deletingRelationships = [];

	public function getEnd(): ? Carbon
	{
		return $this->ends_at;
	}

	public function getStart(): ? Carbon
	{
		return $this->starts_at;
	}

	public function getNodeItemData()
	{
		return [
			'class' => get_class($this),
			'id' => $this->getKey()
		];
	}

	public function getFieldsToReset()
	{
		return [
		];
	}
	public function getModelContainerClass()
	{
		return get_class($this->modelContainer()->getRelated());
	}

	public function getFieldsToFreeze() : array
	{
		return [];
	}

	public function scopeByDate($query, Carbon $date)
	{
		return $query->where(function($_query) use($date)
		{
			$_query->where(function($__query) use($date)
			{
				$__query->where('starts_at', '<=', $date)->where('ends_at', '>=', $date);
			})->orWhere(function($__query) use($date)
			{
				$__query->whereNull('starts_at')->where('ends_at', '>=', $date);				
			})->orWhere(function($__query) use($date)
			{
				$__query->whereNull('ends_at')->where('starts_at', '<=', $date);				
			});
		})->orWhere(function($_query) use($date)
		{
			$_query->having('orders', function($__query) use($date)
			{
				$__query->byDate($date);
			});
		});
	}

	public function scopeBySellableSuppliers($query, Collection $sellableSuppliers)
	{
		if(! is_string($sellableSuppliers->first()))
			$sellableSuppliers = $sellableSuppliers->pluck('id');

		return $query->whereIn('sellable_supplier_id', $sellableSuppliers);
	}

	public function getHistoryUrlPlaceholder()
	{
		$pluralClass = $this->pluralLowerClass();
		$routeKey = $this->getCamelcaseClassBasename();

		try
		{
			return app('products')->route("{$pluralClass}.history", [$routeKey => config('datatables.replace_model_id_string')]);
		}
		catch(\Exception $e)
		{
			Ukn::e('Non esiste la route ' . "{$pluralClass}.history");
		}
	}

	static function boot()
	{
		parent::boot();

		static::deleting(function ($model)
		{
			if ($type = $model->getSellable()?->getType())
			{
				$container = $model->getModelContainer();

				$rows = $container->rows()->bySellableType($type)->orderBy('sorting_index')->get();

				foreach($rows as $index => $row)
					if(! $row->is($model))
					{
						$row->sorting_index = $index;
						$row->saveQuietly();
					}
			}
		});
	}

	public function getPossibleDriversArrayValues() : array
	{
		return $this->getPossibleOperatorsArrayValues();
	}

	public function getPossibleOperatorsArrayValues() : array
	{
		if (! $container = $this->getModelContainer())
			return [];

		return $container->getPossibleOperatorsArrayValues();
	}

	public function getPossiblePassengersArrayValues() : array
	{
		return $this->getPossibleOperatorsArrayValues();
	}

	public function getBackgroundColor()
	{
		return $this->getSupplier()?->getBackgroundColor();
	}

	public function getCssTextColorValue()
	{
		return $this->getSupplier()?->getCssTextColorValue();
	}

	public function getTimelineItemActions(? TimelineGroupInterface $groupModel) : array
	{
		$result = [];

		$result[] = [
			'url' => $this->getAssignSellablesupplierUrl(),
			'text' => 'Cambia fornitore',
			'target' => 'iframe',
			'faIcon' => 'shuffle',
		];

		$result[] = [
			'url' => $this->getModelContainer()->getEditUrl(),
			'text' => 'Vai alla commessa',
			'faIcon' => 'link',
		];

		return $result;
	}

	public function getTimelineItemRightLinks(? TimelineGroupInterface $groupModel) : array
	{
		$rightLinks = [];

		if($sellable = $this->getSupplier())
		{
			$rightLinks[] = [
				'url' => $sellable->getGanttUrl(),
				'target' => 'iframe',
				'text' => 'Esamina veloce',
				'faIcon' => 'magnifying-glass',
			];

			$rightLinks[] = [
				'url' => $sellable->getGanttUrl(),
				'text' => 'Vai alla pagina dedicata',
				'target' => '_blank',
				'faIcon' => 'chart-gantt',
			];
		}

		return $rightLinks;
	}

	public function getTimelineItemGroupId(? TimelineGroupInterface $groupModel) : string
	{
		return $this->getSellable()?->getKey() ?? '';
	}

	public function getCssBackgroundColorValue(? TimelineGroupInterface $groupModel) : ? string
	{
		if($groupModel instanceof Sellable)
			$subject = $this->getSupplier();
		else
			$subject = $this->getSellable();

		return $subject?->getTarget()?->getCssBackgroundColorValue();
	}

	public function getTimelineItemTitle(? TimelineGroupInterface $groupModel) : string
	{
		if($groupModel instanceof Sellable)
			return $this->getSupplierName() ?? 'Nd';

		if($groupModel instanceof Supplier)
			return $this->getSellableName() ?? 'Nd';

		$pieces = [];

		if($value = $this->getSellableName())
			$pieces[] = $value;

		if($value = $this->getSupplierName())
			$pieces[] = $value;

		return trim(implode(' - ', $pieces)) ?? 'Nd';
	}

	public function getSupplierName() : ? string
	{
		return $this->getSupplier()?->getTarget()?->getName();
	}

	public function getSellableName() : ? string
	{
		return $this->getSellable()?->getTarget()?->getName();
	}

	public function getTimelineItemPopuptitle(? TimelineGroupInterface $groupModel) : string
	{
		if($groupModel instanceof Sellable)
			return $this->getSupplierName() ?? 'Nd';

		if($groupModel instanceof Supplier)
			return $this->getSellableName() ?? 'Nd';

		$pieces = [];

		if($value = $this->getSellableName())
			$pieces[] = $value;

		if($value = $this->getSupplierName())
			$pieces[] = $value;

		return trim(implode(' - ', $pieces)) ?? 'Nd';
	}


	public function getTimelineItemHtmlClasses(? TimelineGroupInterface $groupModel) : array
	{
		return [];
	}
}