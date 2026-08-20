<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\Media\InteractsWithMedia;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDTimeRangesTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Events\ProductPackageBaseRowSavedEvent;
use IlBronza\Products\Helpers\Timelines\OrderSellableGroup;
use IlBronza\Products\Helpers\Timelines\OrderSupplierGroup;
use IlBronza\Products\Helpers\Timelines\SellableOrderGroup;
use IlBronza\Products\Helpers\Timelines\SellableSupplierGroup;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\Orderrow\TypedOrderrowTrait;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Timeline\Interfaces\TimelineItemInterface;
use IlBronza\Timeline\Traits\IsTimelineItemTrait;
use IlBronza\Timings\Interfaces\TimeIntervalInterface;
use IlBronza\Timings\Interfaces\TimelineInterface;
use IlBronza\Ukn\Ukn;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use function class_basename;
use function get_class;
use function get_class_methods;
use function is_string;

class ProductPackageBaseRowModel extends ProductPackageBaseModel implements TimeIntervalInterface, TimelineItemInterface, HasMedia
{
	use InteractsWithPriceTrait;
	use IsTimelineItemTrait;
	use CRUDTimeRangesTrait;
	use TypedOrderrowTrait;
	use CRUDParentingTrait;
    use InteractsWithMedia;

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
			if(\Auth::id() == 1)
			{
				Log::critical('Non esiste la route ' . "{$pluralClass}.history");
				// Ukn::e('Non esiste la route ' . "{$pluralClass}.history");
			}
		}
	}

	public function getGenericChildrenUrlPlaceholder()
	{
		return $this->getPlaceholderRoute('genericChildren');
	}

	static function boot()
	{
		parent::boot();

		static::saved(function ($model)
		{
			$model->getModelContainer()->touch();

			ProductPackageBaseRowSavedEvent::dispatch($model);
		});

		static::deleting(function ($model)
		{
			$model->getModelContainer()->touch();

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

	public function getSupplierTimelineGroup() : ?TimelineGroupInterface
	{
		return $this->getSupplier()?->getSupplierTimelineGroup();
	}

	//il figlio passa dal resolver, cosi' l'id del gruppo e' lo stesso
	//che il controller mette nei nestedGroups del bene
	public function getSellableSupplierSubgroupTimelineGroup() : SellableSupplierGroup
	{
		return SellableSupplierGroup::create(
			$this->getSellable(),
			$this->getSupplierTimelineGroup()
		);
	}

	public function getSellableOrderSubgroupTimelineGroup() : SellableOrderGroup
	{
		return SellableOrderGroup::create(
			$this->getSellable(),
			$this->getModelContainer()
		);
	}

	public function getOrderSupplierSubgroupTimelineGroup() : OrderSupplierGroup
	{
		return OrderSupplierGroup::create(
			$this->getModelContainer(),
			$this->getSupplierTimelineGroup()
		);
	}

	public function getOrderSellableSubgroupTimelineGroup() : OrderSellableGroup
	{
		return OrderSellableGroup::create(
			$this->getModelContainer(),
			$this->getSellable()
		);
	}

	public function getTimelineModalButtons() : array
	{
		$buttons = [];

		foreach([
			$this->getSellable(),
			$this->getSupplier(),
			$this->getModelContainer()
		] as $element)
			if($element)
				$buttons[] = $element->getGanttButton();

		return $buttons;
	}

	public function getTimelineItemActions(? TimelineGroupInterface $groupModel) : array
	{
		return [
			[
				'url' => $this->getAssignSellablesupplierUrl(),
				'text' => 'Cambia fornitore',
				'target' => 'iframe',
				'faIcon' => 'shuffle',
			],
			[
				'url' => $this->getModelContainer()->getEditUrl(),
				'text' => 'Vai alla commessa',
				'faIcon' => 'link',
			],
		];
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

	//sotto bene + fornitore entrambi sono gia' detti dal percorso: resta la commessa
	public function getTimelineItemTitleForSellableSupplierGroup(SellableSupplierGroup $group) : string
	{
		return $this->getModelContainer()?->getName() ?? '';
	}

	//sotto bene + commessa resta il fornitore
	public function getTimelineItemTitleForSellableOrderGroup(SellableOrderGroup $group) : string
	{
		return $this->getSupplierName() ?? '';
	}

	//sotto commessa + fornitore resta il bene
	public function getTimelineItemTitleForOrderSupplierGroup(OrderSupplierGroup $group) : string
	{
		return $this->getSellableName() ?? '';
	}

	//sotto commessa + bene resta il fornitore
	public function getTimelineItemTitleForOrderSellableGroup(OrderSellableGroup $group) : string
	{
		return $this->getSupplierName() ?? '';
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
			return $this->getSupplierName() ?? config('products.labels.nd', 'nd');

		if($groupModel instanceof Supplier)
			return $this->getSellableName() ?? config('products.labels.nd', 'nd');

		$pieces = [];

		if($value = $this->getSellableName())
			$pieces[] = $value;

		if($value = $this->getSupplierName())
			$pieces[] = $value;

		if($value = $this->getModelContainer()?->getName())
			$pieces[] = $value;

		return trim(implode(' - ', $pieces)) ?? config('products.labels.nd', 'nd');
	}


	public function getTimelineItemHtmlClasses(? TimelineGroupInterface $groupModel) : array
	{
		return [];
	}

	public function modelContainer()
	{
		return $this->container();
	}

	public function getSupplierSelectedLabel() : string
	{
		return $this->getSupplier()?->getName();
	}

	public function getSupplierIdAttribute()
	{
		return $this->getSupplier()?->getKey();
	}
}
