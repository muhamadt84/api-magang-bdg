<?php

namespace App\Models;

use App\Models\product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'product_images';
    protected $fillable =[
        
    'name',
    'category_id',
    'description',
    'price',
    'discount',
    'rating',
    'brand',
    'member_id',
    'image',
    'image_path', 
    'image_link'

];
}
