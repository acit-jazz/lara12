<?php

namespace App\QueryFilters;

class SearchTitleDesc extends Filter
{
    protected function applyFilter($builder)
    {
        $filterValue = '%' . request($this->filterName()) . '%';

        return $builder->where('title', 'LIKE', $filterValue)
                       ->orWhere('summary', 'LIKE', $filterValue)
                       ->orWhere('description', 'LIKE', $filterValue);
    }
}
