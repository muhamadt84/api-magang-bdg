<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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

}