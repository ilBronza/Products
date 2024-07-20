<?php

namespace IlBronza\Products\Providers\Helpers\TimingEstimators;

use Illuminate\Database\Eloquent\Model;

abstract class TimingEstimator
{
	public Model $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function getModel() : Model
	{
		return $this->model;
	}

	abstract public function getEstimatedTimeSeconds() : float;
}