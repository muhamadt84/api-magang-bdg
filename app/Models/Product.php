<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
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
        'image'

    ];


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
}
