<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog-tags",
     *     tags={"Blog Tags"},
     *     summary="Get list of tags",
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index()
    {
        return response()->json(Tag::all());
    }

    /**
     * @OA\Post(
     *     path="/api/blog-tags",
     *     tags={"Blog Tags"},
     *     summary="Create a new tag",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Tag")
     *     ),
     *     @OA\Response(response=201, description="Tag created successfully")
     * )
     */
    public function store(Request $request)
    {
        $request['slug'] = Str::slug($request->name);
        $tag = Tag::create($request->all());
        return response()->json($tag, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/blog-tags/{id}",
     *     tags={"Blog Tags"},
     *     security={{"sanctum": {}}},
     *     summary="Get a single tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Tag not found")
     * )
     */
    public function show($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }
        return response()->json($tag);
    }

    /**
     * @OA\Put(
     *     path="/api/blog-tags/{id}",
     *     tags={"Blog Tags"},
     *     security={{"sanctum": {}}},
     *     summary="Update a tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Tag")
     *     ),
     *     @OA\Response(response=200, description="Tag updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $request['slug'] = Str::slug($request->name);
        $tag->update($request->all());
        return response()->json($tag, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/blog-tags/{id}",
     *     tags={"Blog Tags"},
     *     security={{"sanctum": {}}},
     *     summary="Delete a tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Tag deleted successfully")
     * )
     */
    public function destroy($id)
    {
        Tag::destroy($id);
        return response()->json(null, 204);
    }
}
