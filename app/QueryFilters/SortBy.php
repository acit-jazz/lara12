<?php

namespace App\QueryFilters;

class SortBy extends Filter
{
    protected function applyFilter($builder)
    {
        //   $countSortBy = count(request($this->filterName())) ?? 0;

        if (is_array(request($this->filterName()))) {
            foreach (request($this->filterName()) as $key => $row) {
                $builder->orderBy($row, request('sort')[$key] ?? 'desc');
            }

            return $builder;
        } else {
            return $builder->orderBy(request($this->filterName()), request('sort') ?? 'desc');
        }
    }
}
