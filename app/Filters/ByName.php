<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByName
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        return $next($builder)
                ->when($this->request->has('name'), function ($query) {
                    $query->where('name', 'like', '%'.$this->request->name.'%');
                });
    }
}
