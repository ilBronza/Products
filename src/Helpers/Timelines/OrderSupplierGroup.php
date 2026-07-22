<?php

namespace IlBronza\Products\Helpers\Timelines;

/**
 * Commessa + fornitore. Il figlio non e' sempre il Supplier: arriva dal
 * resolver getSupplierTimelineGroup(), che puo' restituire il target
 * o una sua relazione.
 * Il nome della classe e' anche il nome del metodo che le righe
 * implementano per il titolo: getTimelineItemTitleForOrderSupplierGroup().
 */
class OrderSupplierGroup extends CompositeTimelineGroup
{
}
