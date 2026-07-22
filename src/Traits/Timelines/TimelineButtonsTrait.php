<?php

namespace IlBronza\Products\Traits\Timelines;

use IlBronza\Buttons\Button;
use Illuminate\Support\Collection;

/**
 * Bottoni di navigazione fra le viste di una stessa timeline.
 * Chi lo usa dichiara la mappa nomeRotta => testo e parametri, e la
 * rotta della vista che sta mostrando.
 */
trait TimelineButtonsTrait
{
	abstract public function getContainerRouteName() : string;

	abstract public function getTimelineButtonsParameters() : array;

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
			'href' => app($this->getPackageConfigName())->route($routeName, $parameters['parameters']),
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
}
