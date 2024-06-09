<?php
namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * @OA\Get(
     *     path="/api/blog-categories",
     *     tags={"Blog Categories"},
     *     security={{"sanctum":{}}},
     *     summary="Get list of categories",
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index()
    {
        return response()->json(Category::all());
    }

    /**
     * @OA\Post(
     *     path="/api/blog-categories",
     *     tags={"Blog Categories"},
     *     security={{"sanctum":{}}},
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(response=201, description="Category created successfully")
     * )
     */
    public function store(Request $request)
    {
        $request->slug = Str::slug($request->name);
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/vlog-categories/{id}",
     *     tags={"Blog Categories"},
     *     security={{"sanctum":{}}},
     *     summary="Get a single category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    /**
     * @OA\Put(
     *     path="/api/blog-categories/{id}",
     *     tags={"Blog Categories"},
     *     summary="Update a category",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(response=200, description="Category updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $request['slug'] = Str::slug($request->name);
        $request['author_id'] = auth()->user()->id;
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json($category, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/blog-categories/{id}",
     *     tags={"Blog Categories"},
     *     summary="Delete a category",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Category deleted successfully")
     * )
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return response()->json(null, 204);
    }
}
