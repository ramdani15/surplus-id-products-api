<?php

namespace App\Traits;

trait DatatableTrait
{
    /**
     * Filter Datatable
     *
     * @param  object  $obj
     * @param  array  $filter
     * @return object
     */
    public function filterDatatable($query, $piplines, $request)
    {
        // set default value
        $limit = $request->limit ?? 10;
        $request->sort_by = $request->sort_by ?? 'id';
        $request->sort = $request->sort ?? -1;

        // Filter process
        $data = \Illuminate\Support\Facades\Pipeline::send($query)
                                                    ->through($piplines)
                                                    ->thenReturn();

        return $data->paginate($limit)->appends($request->input());
    }
}
