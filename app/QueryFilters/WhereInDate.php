<?php

namespace App\QueryFilters;

use Illuminate\Support\Carbon;

class WhereInDate extends Filter
{
    protected function applyFilter($builder)
    {
        if (request($this->filterName())) {
            $date = explode('-',request($this->filterName()));
            $start = Carbon::parse($date[0]);
            $end = Carbon::parse($date[1]);
            $column = request('column') ?? 'created_at';
            return $builder->whereDate($column, '>=', $start)->whereDate($column, '<=', $end);
        }
        return $builder;

    }
}
