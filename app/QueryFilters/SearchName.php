<?php

namespace App\QueryFilters;

class SearchName extends Filter
{
    protected function applyFilter($builder)
    {
        return $builder->where('name', 'LIKE', '%' . request($this->filterName()) . '%');
    }
}
