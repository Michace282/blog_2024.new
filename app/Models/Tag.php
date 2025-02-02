<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Tag",
 *     required={"name"},
 *     @OA\Property(property="name", type="string")
 * )
 */

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'created_at', 'updated_at'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }

    public static function getTotalRows()
    {
        return self::count();
    }
}
