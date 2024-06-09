<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Post",
 *     required={"title", "meta_description", "description", "category_id", "is_publish"},
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="meta_description", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="category_id", type="integer"),
 *     @OA\Property(property="featured_image", type="string", nullable=true),
 *     @OA\Property(property="is_publish", type="boolean"),
 *     @OA\Property(
 *         property="tags",
 *         type="array",
 *         @OA\Items(type="integer")
 *     )
 * )
 */

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'title', 'slug', 'meta_description', 'description', 'category_id', 'featured_image', 'is_publish', 'published_at', 'author_id'
    ];

    public static function getPostList()
    {
        return self::with(['category', 'tag', 'user'])
            ->select(
                'posts.id',
                'posts.title',
                'posts.slug',
                'posts.meta_description',
                'posts.description',
                'posts.is_publish',
                'posts.published_at',
                'posts.category_id',
                'posts.featured_image',
                'posts.created_at',
                'tags.name as tag_name',
                'categories.name as category_name'
            )
            ->join('tags', 'posts.type_id', '=', 'tags.id')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->orderBy('posts.id')
            ->get();
    }

    public static function getPostListByAuthor($author_id)
    {
        return self::with(['category', 'tag', 'user'])
            ->select(
                'posts.id',
                'posts.title',
                'posts.slug',
                'posts.meta_description',
                'posts.description',
                'posts.is_publish',
                'posts.published_at',
                'posts.category_id',
                'posts.featured_image',
                'posts.created_at',
                'tags.name as tag_name',
                'categories.name as category_name'
            )
            ->join('tags', 'posts.type_id', '=', 'tags.id')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->where('posts.author_id', $author_id)
            ->orderBy('posts.id')
            ->get();
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('description', 'like', '%' . $keyword . '%');
        });
    }

    public static function getTotalRows()
    {
        return self::count();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
