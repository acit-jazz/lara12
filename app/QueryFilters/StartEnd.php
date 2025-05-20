<?php

namespace App\QueryFilters;

use Illuminate\Support\Carbon;

class StartEnd extends Filter
{
    protected function applyFilter($builder)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        if (request($this->filterName())) {
            $dateNow = $this->filterName();
        }

        return $builder->whereDate('started_at', '>=', $dateNow)->whereDate('ended_at', '<=', $dateNow);
    }
}
