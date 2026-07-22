<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Buttons\Button;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;
use Illuminate\Support\Collection;

class GlobalSellableTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	//senza questo il titolo pagina cerca routes.xxx invece di products::routes.xxx
	public function getPackageConfigName()
	{
		return 'products';
	}

	public function getEndpoint() : string
	{
		return app('products')->route('sellables.globalTimeline');
	}

	public function getContainerRouteName() : string
	{
		return 'sellables.globalTimelineContainer';
	}

	public function getTimelineButtonsParameters() : array
	{
		return [
			'sellables.globalTimelineContainer' => [
				'text' => 'products::timeline.sellables',
				'parameters' => [],
			],
			'sellables.bySuppliersTimelineContainer' => [
				'text' => 'products::timeline.sellablesBySuppliers',
				'parameters' => ['option' => 'subgroups'],
			],
			'sellables.byOrdersTimelineContainer' => [
				'text' => 'products::timeline.sellablesByOrders',
				'parameters' => ['option' => 'subgroups'],
			],
		];
	}

	//il bottone della timeline che si sta guardando resta visibile ma disabilitato
	public function getButtons() : Collection
	{
		$activeRouteName = $this->getContainerRouteName();

		return collect($this->getTimelineButtonsParameters())
			->map(fn(array $button, string $routeName) => $this->getTimelineButton(
				$routeName, $button, $routeName == $activeRouteName
			))
			->values();
	}

	public function getTimelineButton(string $routeName, array $parameters, bool $active) : Button
	{
		$button = Button::create([
			'href' => app('products')->route($routeName, $parameters['parameters']),
			'text' => $parameters['text'],
		]);

		$button->setSecondary();
		$button->setSmall();

		if(! $active)
			return $button;

		//disabled da solo non blocca un <a>, serve la classe uikit
		$button->setDisabled();
		$button->setHtmlClass('uk-disabled');

		return $button;
	}

	public function getMainTimelineData()
	{
		$addContainerGantt = true;

		$ids = Orderrow::gpc()::select('id')->pluck('id');

		$orderrows = RowsFinderHelper::getCompositeRowCollectionByIds($ids);
		// $orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();

		$groupItems = Sellable::gpc()::with('target')->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getSellable');

		return $this->sendResponse();
	}

	public function getTimelineCreateRowFormEndpoint() : ?string
	{
		return app('products')->route('sellables.timeline.createRowFormBySellable', [
			'iframed' => true,
		]);
	}
}
