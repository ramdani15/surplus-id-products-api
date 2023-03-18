<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Requests\Api\V1\Product\CreateRequest;
use App\Http\Requests\Api\V1\Product\UpdateRequest;
use Facades\App\Repositories\Api\V1\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *       path="/api/v1/products",
     *       summary="Get list products ",
     *       description="Endpoint to get list products ",
     *       tags={"Products"},
     *
     *       @OA\Parameter(
     *           name="id",
     *           in="query",
     *           description="ID"
     *       ),
     *       @OA\Parameter(
     *           name="name",
     *           in="query",
     *           description="Name"
     *       ),
     *       @OA\Parameter(
     *           name="description",
     *           in="query",
     *           description="Description"
     *       ),
     *       @OA\Parameter(
     *           name="enable",
     *           in="query",
     *           description="Enable (1 / 0)"
     *       ),
     *       @OA\Parameter(
     *           name="sort",
     *           in="query",
     *           description="1 for Ascending -1 for Descending"
     *       ),
     *       @OA\Parameter(
     *           name="sort_by",
     *           in="query",
     *           description="Field to sort"
     *       ),
     *       @OA\Parameter(
     *           name="limit",
     *           in="query",
     *           description="Limit (Default 10)"
     *       ),
     *       @OA\Parameter(
     *           name="page",
     *           in="query",
     *           description="Num Of Page"
     *       ),
     *
     *       @OA\Response(
     *          response=200,
     *          description="Get list products successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="data", type="object", example={}),
     *              @OA\Property(property="pagination", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Get list products failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Get list products failed"),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $data = ProductRepository::datatable($request);
        if (isset($data['status']) && ! $data['status']) {
            return $this->responseJson('error', $data['message'], $data['data']);
        }

        return $this->responseJson(
            'pagination',
            __('Get list products successfully'),
            $data,
            200,
            [$request->sort_by, $request->sort]
        );
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products",
     *      summary="Create product",
     *      description="Endpoint to handle create product",
     *      tags={"Products"},
     *
     *       @OA\RequestBody(
     *
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     *
     *               @OA\Schema(
     *                   required={"name", "description", "enable", "image", "category_id"},
     *
     *                   @OA\Property(property="name", type="string", description="Name", example="new product"),
     *                   @OA\Property(property="description", type="string", description="Description", example="description"),
     *                   @OA\Property(property="enable", type="number", description="Enable (1 / 0)", example=1),
     *                   @OA\Property(property="image", type="file", description="Image"),
     *                   @OA\Property(property="category_id", type="number", description="Category ID", example=1),
     *               )
     *           )
     *       ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Create product successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Create product successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Create product failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Create product failed"),
     *          )
     *      ),
     * )
     */
    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $response = ProductRepository::create($data);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['data'],
            $response['status'] ? 201 : 400,
        );
    }

    /**
     * @OA\Get(
     *       path="/api/v1/products/{id}",
     *       summary="Get detail product ",
     *       description="Endpoint to get detail product ",
     *       tags={"Products"},
     *
     *      @OA\Parameter(
     *          required=true,
     *          name="id",
     *          in="path",
     *          description="ID"
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Get product successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Get Product successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Product not found",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Product not found"),
     *          )
     *      ),
     * )
     */
    public function show($id)
    {
        $response = ProductRepository::find($id);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['data'],
            $response['status'] ? 200 : 404,
        );
    }

    // NOTE : only can POST method for form data
    /**
     * @OA\Post(
     *       path="/api/v1/products/{id}",
     *       summary="Update product ",
     *       description="Endpoint to update product ",
     *       tags={"Products"},
     *
     *      @OA\Parameter(
     *          required=true,
     *          name="id",
     *          in="path",
     *          description="ID"
     *      ),
     *
     *       @OA\RequestBody(
     *
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     *
     *               @OA\Schema(
     *                   required={"name", "description", "enable"},
     *
     *                   @OA\Property(property="name", type="string", description="Name", example="new product"),
     *                   @OA\Property(property="description", type="string", description="Description", example="description"),
     *                   @OA\Property(property="enable", type="number", description="Enable (1 / 0)", example=1),
     *                   @OA\Property(property="image", type="file", description="Image (only for Replace)"),
     *                   @OA\Property(property="category_id", type="number", description="Category ID (only for Replace)"),
     *               )
     *           )
     *       ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Update product successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Update Product successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Update product failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Update product failed"),
     *          )
     *      ),
     * )
     */
    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();
        $response = ProductRepository::update($id, $data);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['data'],
            $response['status'] ? 200 : 400,
        );
    }

    /**
     * @OA\Delete(
     *       path="/api/v1/products/{id}",
     *       summary="Delete product ",
     *       description="Endpoint to delete product ",
     *       tags={"Products"},
     *
     *      @OA\Parameter(
     *          required=true,
     *          name="id",
     *          in="path",
     *          description="ID"
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Delete product successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Delete Product successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Delete product failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Delete product failed"),
     *          )
     *      ),
     * )
     */
    public function destroy($id)
    {
        $response = ProductRepository::delete($id);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['data'],
            $response['status'] ? 200 : 400,
        );
    }
}
