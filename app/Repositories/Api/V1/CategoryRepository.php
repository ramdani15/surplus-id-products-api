<?php

namespace App\Repositories\Api\V1;

use App\Filters\ByEnable;
use App\Filters\ByName;
use App\Filters\OrderBy;
use App\Models\Category;
use App\Resources\Api\V1\CategoryResource;
use App\Traits\DatatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Categories
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function datatable(Request $request)
    {
        try {
            $query = Category::query();
            $piplines = [
                ByName::class,
                ByEnable::class,
                OrderBy::class,
            ];

            $data = $this->filterDatatable($query, $piplines, $request);

            return CategoryResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed get categories'), $th->getMessage());
        }
    }
}
