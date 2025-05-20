<?php

namespace App\QueryFilters;

use Illuminate\Support\Carbon;

class StartEndEvent extends Filter
{
    protected function applyFilter($builder)
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        if (request($this->filterName())) {
            $dateNow = request($this->filterName());
        }
        // dd($dateNow);

        return $builder->whereDate('ended_at', '>=', $dateNow);
    }
}
