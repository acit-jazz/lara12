<?php

namespace App\QueryFilters;
use Illuminate\Support\Str;
use Closure;

class SearchDesc extends Filter
{
   protected function applyFilter($builder)
   {
    return $builder->where('description', 'LIKE', '%' . request($this->filterName()) . '%');
   }
}
