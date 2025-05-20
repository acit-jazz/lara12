<?php

namespace App\QueryFilters;

class SearchNameDesc extends Filter
{
    protected function applyFilter($builder)
    {
        $filterValue = '%' . request($this->filterName()) . '%';

        return $builder->where('name', 'LIKE', $filterValue)
                       ->orWhere('summary', 'LIKE', $filterValue)
                       ->orWhere('description', 'LIKE', $filterValue);
    }
}
