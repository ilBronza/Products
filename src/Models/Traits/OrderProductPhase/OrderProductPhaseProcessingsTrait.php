<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

trait OrderProductPhaseProcessingsTrait
{
	public function getCalculatedValidPiecesDoneAttribute() : ? int
	{
		if(! $this->isCompleted())
			return null;

		return $this->getProcessings()->sum('valid_pieces_done');
	}

	public function getCalculatedValidPiecesDone() : ? int
	{
		return $this->calculated_valid_pieces_done;
	}
}