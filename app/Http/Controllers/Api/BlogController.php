<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * @OA\Get(
     *     path="/api/blog-posts",
     *     tags={"Blog Posts"},
     *     summary="Get list of posts",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index()
    {
        return response()->json(Post::with('category', 'tags')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/blog-posts",
     *     tags={"Blog Posts"},
     *     summary="Create a new post",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(response=201, description="Post created successfully")
     * )
     */
    public function store(Request $request)
    {

        $request['slug'] = Str::slug($request->title);
        $request['author_id'] = auth()->user()->id;

        $post = Post::create($request->all());
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }
        return response()->json($post->load('category', 'tags'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/vlog-posts/{id}",
     *     tags={"Blog Posts"},
     *     security={{"sanctum":{}}},
     *     summary="Get a single post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function show($id)
    {
        return response()->json(Post::with('category', 'tags')->find($id));
    }

    /**
     * @OA\Put(
     *     path="/api/blog-posts/{id}",
     *     tags={"Blog Posts"},
     *     security={{"sanctum":{}}},
     *     summary="Update a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(response=200, description="Post updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $request['slug'] = Str::slug($request->name);
        $request['author_id'] = auth()->user()->id;
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'meta_description' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'featured_image' => 'nullable|string',
            'is_publish' => 'sometimes|required|boolean',
            'published_at' => 'nullable|date',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('category', 'tags'), 200);
        return response()->json($post, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/blog-posts/{id}",
     *     tags={"Blog Posts"},
     *     summary="Delete a post",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Post deleted successfully")
     * )
     */
    public function destroy($id)
    {
        Post::destroy($id);
        return response()->json(null, 204);
    }
}
