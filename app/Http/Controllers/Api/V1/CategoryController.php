<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
use App\Http\Requests\Api\V1\Category\CreateRequest;
use App\Http\Requests\Api\V1\Category\UpdateRequest;
use Facades\App\Repositories\Api\V1\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *       path="/api/v1/categories",
     *       summary="Get list categories ",
     *       description="Endpoint to get list categories ",
     *       tags={"Categories"},
     *
     *       @OA\Parameter(
     *           name="id",
     *           in="query",
     *           description="ID"
     *       ),
     *       @OA\Parameter(
     *           name="name",
     *           in="query",
     *           description="name"
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
     *          description="Get list categories successfully",
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
     *          description="Get list categories failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Get list categories failed"),
     *          )
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $data = CategoryRepository::datatable($request);
        if (isset($data['status']) && ! $data['status']) {
            return $this->responseJson('error', $data['message'], $data['data']);
        }

        return $this->responseJson(
            'pagination',
            __('Get list categories successfully'),
            $data,
            200,
            [$request->sort_by, $request->sort]
        );
    }

    /**
     * @OA\Post(
     *      path="/api/v1/categories",
     *      summary="Create category",
     *      description="Endpoint to handle create category",
     *      tags={"Categories"},
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *              required={"name", "enable"},
     *
     *              @OA\Property(property="name", type="string", example="new category"),
     *              @OA\Property(property="enable", type="boolean", example=1),
     *          ),
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Create category successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Create category successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Create category failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Create category failed"),
     *          )
     *      ),
     * )
     */
    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $response = CategoryRepository::create($data);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['status'] ? $response['data'] : null,
            $response['status'] ? 201 : 400,
        );
    }

    /**
     * @OA\Get(
     *       path="/api/v1/categories/{id}",
     *       summary="Get detail category ",
     *       description="Endpoint to get detail category ",
     *       tags={"Categories"},
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
     *          description="Get category successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Get Category successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Category not found",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Category not found"),
     *          )
     *      ),
     * )
     */
    public function show($id)
    {
        $response = CategoryRepository::find($id);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['status'] ? $response['data'] : null,
            $response['status'] ? 200 : 404,
        );
    }

    /**
     * @OA\Put(
     *       path="/api/v1/categories/{id}",
     *       summary="Update category ",
     *       description="Endpoint to update category ",
     *       tags={"Categories"},
     *
     *      @OA\Parameter(
     *          required=true,
     *          name="id",
     *          in="path",
     *          description="ID"
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *              required={"name", "enable"},
     *
     *              @OA\Property(property="name", type="string", example="new category"),
     *              @OA\Property(property="enable", type="boolean", example=1),
     *          ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Update category successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Update Category successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Update category failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Update category failed"),
     *          )
     *      ),
     * )
     */
    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();
        $response = CategoryRepository::update($id, $data);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['status'] ? $response['data'] : null,
            $response['status'] ? 200 : 400,
        );
    }

    /**
     * @OA\Delete(
     *       path="/api/v1/categories/{id}",
     *       summary="Delete category ",
     *       description="Endpoint to delete category ",
     *       tags={"Categories"},
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
     *          description="Delete category successfully",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Delete Category successfully"),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Delete category failed",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Delete category failed"),
     *          )
     *      ),
     * )
     */
    public function destroy($id)
    {
        $response = CategoryRepository::delete($id);

        return $this->responseJson(
            $response['status'] ? 'success' : 'error',
            $response['message'],
            $response['status'] ? $response['data'] : null,
            $response['status'] ? 200 : 400,
        );
    }
}
