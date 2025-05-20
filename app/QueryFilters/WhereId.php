<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Support\Carbon;

class WhereId extends Filter
{
   protected function applyFilter($builder)
   {
      return $builder->whereIn('id',explode(',',request($this->filterName())));
   }
}
