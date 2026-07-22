<?php

namespace IlBronza\Products\Helpers\Timelines;

/**
 * Bene + fornitore. Il figlio non e' sempre il Supplier: arriva dal
 * resolver getSupplierTimelineGroup(), che puo' restituire il target
 * o una sua relazione.
 * Il nome della classe e' anche il nome del metodo che le righe
 * implementano per il titolo: getTimelineItemTitleForSellableSupplierGroup().
 */
class SellableSupplierGroup extends CompositeTimelineGroup
{
}
