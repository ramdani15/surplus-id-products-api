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

    /**
     * Create Category
     *
     * @param  array  $data
     */
    public function create($data)
    {
        try {
            $category = Category::create($data);
            $data = new CategoryResource($category);

            return $this->setResponseSuccess(__('Create category successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed create category'), $th->getMessage());
        }
    }

    /**
     * Find Category
     *
     * @param  int  $id
     */
    public function find($id)
    {
        try {
            $category = Category::find($id);

            if (! $category) {
                return $this->setResponseError(__('Category not found'));
            }

            $data = new CategoryResource($category);

            return $this->setResponseSuccess(__('Get category successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed get category'), $th->getMessage());
        }
    }

    /**
     * Update Category
     *
     * @param  int  $id
     * @param  array  $data
     */
    public function update($id, $data)
    {
        try {
            $category = Category::find($id);

            if (! $category) {
                return $this->setResponseError(__('Category not found'));
            }

            $category->update($data);

            $category = Category::find($id);
            $data = new CategoryResource($category);

            return $this->setResponseSuccess(__('Update category successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed update category'), $th->getMessage());
        }
    }

    /**
     * Delete Category
     *
     * @param  int  $id
     */
    public function delete($id)
    {
        try {
            $category = Category::find($id);

            if (! $category) {
                return $this->setResponseError(__('Category not found'));
            }

            $category->delete();

            return $this->setResponseSuccess(__('Delete category successfully'));
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed delete category'), $th->getMessage());
        }
    }
}
