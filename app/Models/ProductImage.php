<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
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
    

];
public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
