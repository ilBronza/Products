<?php

namespace IlBronza\Products\Models\Traits;

use App\Models\ProductsPackage\OrderProductExtraFields;
use Carbon\Carbon;

trait CompletionScopesTrait
{
    public function scopeWithCompletedAt($query)
    {
        dddl("ora è nella tabella stessa il campo in questione");

        $query->addSelect([
            'live_completed_at' => OrderProductExtraFields::select('completed_at')
                    ->whereColumn('order_products.order_product_id', $this->getTable() . '.id')
                    ->take(1)
        ]);
    }

    public function scopeCompletedByDate($_query, Carbon $date)
    {
        // $query->whereHas('extraFields', function($_query) use($date)
        // {
            $_query->whereDate('completed_at', $date);
        // });
    }

    public function scopeCompletedToday($query)
    {
        $query->completedByDate(Carbon::today());
    }

    public function scopeCompleted($_query)
    {
        // $query->whereHas('extraFields', function($_query)
        // {
            $_query->whereNotNull('completed_at');
        // });
    }

    public function scopeNotCompleted($_query)
    {
        // $query->whereHas('extraFields', function($_query)
        // {
            $_query->whereNull(static::getProjectClassName()::make()->getTable() . '.completed_at');
        // });
    }

    public function scopeNotCompletedOrToday($_query)
    {
        // $query->whereHas('extraFields', function($_query)
        // {
            $_query     ->whereNull('completed_at')
                        ->orWhereDate('completed_at', Carbon::today()->format('Y-m-d'))
                        ;
        // });
    }

    public function scopeCompletedOrPartiallyCompletedDate($query, Carbon $date)
    {
        $query->whereDate('completed_at', $date);
        $query->orWhereHas('extraFields', function($_query) use($date)
        {
            $_query->whereDate('partially_completed_at', $date);
        });
    }

    public function scopeOrdered($query)
    {
        $query->whereDate('completed_at', $date);
        $query->orWhereHas('extraFields', function($_query) use($date)
        {
            // $_query->where(function($__query)
            // {
            //     $__query->whereDate('completed_at', $date);
                // $__query->orWhereDate('partially_completed_at', $date);
                $_query->orWhereDate('partially_completed_at', $date);
            // });
        });
    }

    public function scopeCompletedDate($query, Carbon $date)
    {
        $query->whereDate('completed_at', $date);
        $query->orWhereHas('extraFields', function($_query) use($date)
        {
            // $_query->where(function($__query) use ($date)
            // {
                // $__query->whereDate('completed_at', $date);
                // $__query->orWhereDate('partially_completed_at', $date);
                $_query->orWhereDate('partially_completed_at', $date);
            // });
        });
    }

    public function scopeByCompletionPeriod($_query, Carbon $from = null, Carbon $to = null)
    {
        // $query->whereHas('extraFields', function($_query) use($from, $to)
        // {
            if($from)
                $_query->whereDate('completed_at', '>=', $from);

            if($to)
                $_query->whereDate('completed_at', '<=', $to);
        // });
    }

    public function scopeCompletedAfter($_query, Carbon $from = null)
    {
        // $query->whereHas('extraFields', function($_query) use($from)
        // {
            $_query->whereDate('completed_at', '>=', $from);
        // });
    }

    public function scopeCompletedBefore($_query, Carbon $to = null)
    {
        // $query->whereHas('extraFields', function($_query) use($to)
        // {
            $_query->whereDate('completed_at', '<=', $to);
        // });
    }

    public function scopeCompletedDateInterval($_query, array $dates)
    {
        // $query->whereHas('extraFields', function($_query) use($dates)
        // {
            $_query->where(function($__query) use ($dates)
            {
                $__query->where(function($___query) use($dates)
                {
                    $___query->whereDate('completed_at', '>=', $dates['date_from'])
                            ->orWhereDate('partially_completed_at', '>=', $dates['date_from']);
                });

                $__query->where(function($___query) use ($dates)
                {
                    $___query->whereDate('completed_at', '<=', $dates['date_to'])
                            ->orWhereDate('partially_completed_at', '<=', $dates['date_to']);
                });
            });

        // });
    }

    public function scopeStarted($_query)
    {
        // $query->whereHas('extraFields', function($_query)
        // {
            $_query->whereNotNull('started_at');
        // });
    }

    public function scopeActive($_query)
    {
        // $query->whereHas('extraFields', function($_query)
        // {
            $_query->whereNull('completed_at');
            $_query->orWhere('completed_at', '>=', Carbon::today()->subDay(2));
        // });
    }






























    public function scopeStartedBeforeToday($query)
    {
        $query->startedBeforeDate(Carbon::now());
    }

    public function scopeStartedBeforeDate($query, Carbon $to)
    {
        $query->whereHas('extraFields', function($_query) use ($to)
        {
            $_query->whereDate('started_at', '<', $to);
        });
    }

    public function scopeStartedToday($query)
    {
        $query->startedAtDate(Carbon::now());
    }

    public function scopeStartedAtDate($query, Carbon $date)
    {
        $query->whereHas('extraFields', function($_query) use($date)
        {
            $_query->whereDate('started_at', $date);
        });
    }

}