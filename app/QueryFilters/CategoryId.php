<?php

namespace App\QueryFilters;

use Closure;

class CategoryId extends Filter
{
   protected function applyFilter($builder)
   {
     return $builder->where('category_id', request($this->filterName()));
   }
}
