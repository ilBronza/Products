<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use IlBronza\Buttons\Button;

trait OrderProductPhaseButtonsAndRouting
{
	public function getStartProcessingButton() : Button
	{
        return Button::create([
            'href' => route('orderProductPhases.processings.startEmpty', [$this]),
            'translatedText' => __('processings.addByWorkstation', ['workstation' => $this->getWorkstationId()]),
            'icon' => 'plus'
        ]);
	}

	public function getReopenButton() : Button
	{
        return Button::create([
            'href' => $this->getReopenUrl(),
            'translatedText' => trans('products::orderProductPhases.reopen'),
            'icon' => 'lock'
        ]);
	}

	public function getCompleteButton() : Button
	{
        return Button::create([
            'href' => $this->getCompleteUrl(),
            'translatedText' => trans('products::orderProductPhases.complete'),
            'icon' => 'play'
        ]);
	}

	public function getReopenMachineInitializationUrl()
	{
		return app('products')->route('orderProductPhases.reopenMachineInitialization', [$this]);
	}

	public function getReopenUrl()
	{
		return app('products')->route('orderProductPhases.reopen', [$this]);
	}

	public function getCompleteUrl()
	{
		return app('products')->route('orderProductPhases.complete', [$this]);
	}
}