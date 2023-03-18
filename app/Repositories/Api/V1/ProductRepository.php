<?php

namespace App\Repositories\Api\V1;

use App\Filters\ByDescription;
use App\Filters\ByEnable;
use App\Filters\ByName;
use App\Filters\OrderBy;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Resources\Api\V1\ProductResource;
use App\Traits\DatatableTrait;
use Facades\App\Repositories\Api\V1\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Datatables Products
     */
    public function datatable(Request $request)
    {
        try {
            $query = Product::query();
            $piplines = [
                ByName::class,
                ByDescription::class,
                ByEnable::class,
                OrderBy::class,
            ];

            $data = $this->filterDatatable($query, $piplines, $request);

            return ProductResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed get products'), $th->getMessage());
        }
    }

    /**
     * Create Product
     *
     * @param  array  $data
     */
    public function create($data)
    {
        \DB::beginTransaction();
        try {
            $product = Product::create($data);

            $image = ImageRepository::upload($data['image']);

            ProductImage::create([
                'product_id' => $product->id,
                'image_id' => $image->id,
            ]);

            CategoryProduct::create([
                'product_id' => $product->id,
                'category_id' => $data['category_id'],
            ]);

            $data = new ProductResource($product);

            \DB::commit();

            return $this->setResponseSuccess(__('Create product successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponseError(__('Failed create product'), $th->getMessage());
        }
    }

    /**
     * Find Product
     *
     * @param  int  $id
     */
    public function find($id)
    {
        try {
            $product = Product::with(['categories', 'images'])->find($id);

            if (! $product) {
                return $this->setResponseError(__('Product not found'));
            }

            $data = new ProductResource($product);

            return $this->setResponseSuccess(__('Get product successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed get product'), $th->getMessage());
        }
    }

    /**
     * Update Product
     *
     * @param  int  $id
     * @param  array  $data
     */
    public function update($id, $data)
    {
        \DB::beginTransaction();
        try {
            $product = Product::find($id);

            if (! $product) {
                return $this->setResponseError(__('Product not found'));
            }

            $product->update($data);

            if (isset($data['image'])) {
                $image = ImageRepository::upload($data['image']);

                // Delete old data
                $product->productImages()->delete();
                $product->images()->delete();

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_id' => $image->id,
                ]);
            }

            if (isset($data['category_id'])) {
                // Delete old data
                $product->categoryProducts()->delete();

                CategoryProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $data['category_id'],
                ]);
            }

            $product = Product::find($id);
            $data = new ProductResource($product);

            \DB::commit();

            return $this->setResponseSuccess(__('Update product successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponseError(__('Failed update product'), $th->getMessage());
        }
    }

    /**
     * Delete Product
     *
     * @param  int  $id
     */
    public function delete($id)
    {
        try {
            $product = Product::find($id);

            if (! $product) {
                return $this->setResponseError(__('Product not found'));
            }

            $product->delete();

            return $this->setResponseSuccess(__('Delete product successfully'));
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponseError(__('Failed delete product'), $th->getMessage());
        }
    }
}
