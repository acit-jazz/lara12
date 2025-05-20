<?php

namespace App\QueryFilters;

use Closure;

class Type extends Filter
{
   protected function applyFilter($builder)
   {
      $types = explode(',',request($this->filterName()));
      return $builder->whereIn('type', $types);
   }
}
