<?php

namespace App\Http\Controllers\Api\V1;

use App\Cores\ApiResponse;
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
}
