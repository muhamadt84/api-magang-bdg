<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use softdeletes;
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'discount',
        'rating',
        'brand',
        'member_id',
        'created_at',
        'updated_at',


    ];

    /**
     * Get the writer that owns the post
     *
     *@return BelongsTo*/
    public function category(): BelongsTo
    {
        return $this->belongsTo(TableCategory::class,'categories_id', 'id');
    }
}