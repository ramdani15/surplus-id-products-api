<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByEnable
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        return $next($builder)
                ->when(isset($this->request->enable), function ($query) {
                    $query->where('enable', (int) $this->request->enable);
                });
    }
}