<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Category",
 *     required={"name"},
 *     @OA\Property(property="name", type="string")
 * )
 */

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public static function getTotalRows()
    {
        return self::count();
    }
}
