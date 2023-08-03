<?php

namespace IlBronza\Products\Models\Traits;

use App\Models\ProductsPackage\OrderProductExtraFields;
use Carbon\Carbon;

trait CompletionScopesTrait
{
    public function scopeCompletedByDate($_query, Carbon $date)
    {
            $_query->whereDate(static::make()->getTable() . '.completed_at', $date);
    }

    public function scopeCompletedToday($query)
    {
        $query->completedByDate(Carbon::today());
    }

    public function scopeCompleted($_query)
    {
            $_query->whereNotNull(static::make()->getTable() . '.completed_at');
    }

    public function scopeNotCompleted($_query)
    {
            $_query->whereNull(static::getProjectClassName()::make()->getTable() . '.completed_at');
    }

    public function scopeNotCompletedOrToday($_query)
    {
            $_query     ->whereNull(static::make()->getTable() . '.completed_at')
                        ->orWhereDate(static::make()->getTable() . '.completed_at', Carbon::today()->format('Y-m-d'))
                        ;
    }

    public function scopeCompletedOrPartiallyCompletedDate($query, Carbon $date)
    {
        $query->whereDate(static::make()->getTable() . '.completed_at', $date);
        $query->orWhereHas('extraFields', function($_query) use($date)
        {
            $_query->whereDate('partially_completed_at', $date);
        });
    }

    public function scopeOrdered($query)
    {
        $query->whereDate(static::make()->getTable() . '.completed_at', $date);
        $query->orWhereHas('extraFields', function($_query) use($date)
        {
                $_query->orWhereDate('partially_completed_at', $date);
        });
    }

    public function scopeCompletedDate($query, Carbon $date)
    {
        $query->whereDate(static::make()->getTable() . '.completed_at', $date);
        $query->orWhereHas('extraFields', function($_query) use($date)
        {
            $_query->whereDate('partially_completed_at', $date);
        });
    }

    public function scopeByCompletionPeriod($_query, Carbon $from = null, Carbon $to = null)
    {
            if($from)
                $_query->whereDate(static::make()->getTable() . '.completed_at', '>=', $from);

            if($to)
                $_query->whereDate(static::make()->getTable() . '.completed_at', '<=', $to);
    }

    public function scopeCompletedAfter($_query, Carbon $from = null)
    {
            $_query->whereDate(static::make()->getTable() . '.completed_at', '>=', $from);
    }

    public function scopeCompletedBefore($_query, Carbon $to = null)
    {
            $_query->whereDate(static::make()->getTable() . '.completed_at', '<=', $to);
    }

    public function scopeCompletedDateInterval($query, array $dates)
    {
        $query->where(function($_query) use($dates)
        {
            $_query->whereDate(static::make()->getTable() . '.completed_at', '>=', $dates['date_from'])
                // ->orWhereDate('partially_completed_at', '>=', $dates['date_from'])
            ;
        });

        $query->where(function($_query) use ($dates)
        {
            $_query->whereDate(static::make()->getTable() . '.completed_at', '<=', $dates['date_to'])
                // ->orWhereDate('partially_completed_at', '<=', $dates['date_to'])
            ;
        });
    }

    public function scopeStarted($_query)
    {
            $_query->whereNotNull('started_at');
    }

    public function scopeActive($_query)
    {
        $_query->whereNull(static::make()->getTable() . '.completed_at');
        $_query->orWhere(static::make()->getTable() . '.completed_at', '>=', Carbon::today()->subDay(2));
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

    public function isCompleted()
    {
        if(!! $this->completed_at)
            return true;
    }

    public function setCompletedAt($value = null, bool $save = false)
    {
        $this->completed_at = $value;

        if($save)
            $this->save();
    }

    public function getCompletedAt() : ? Carbon
    {
        return $this->completed_at;
    }

}