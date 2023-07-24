<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'products';

    protected $fillable = [
    
        'name',
        'category_id',
        'description',
        'price',
        'discount',
        'rating',
        'brand',
        'member_id',
        'image',
        'image',

    ];
    

    public function image()
    {
        return $this->hasOne(ProductImage::class);
    }

}