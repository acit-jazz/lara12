<?php

namespace App\QueryFilters;

use Illuminate\Support\Carbon;

class StartDate extends Filter
{
    protected function applyFilter($builder)
    {
        if (request($this->filterName())) {
            $dateNow = Carbon::parse(request($this->filterName()));
            return $builder->whereDate('started_at', $dateNow);
        }

        return $builder;
    }
}
