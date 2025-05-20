<?php

namespace App\QueryFilters;

use Closure;

class Size extends Filter
{
   protected function applyFilter($builder)
   {
      return $builder->where('size', request($this->filterName()));
   }
}
