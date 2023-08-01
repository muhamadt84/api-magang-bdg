<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes ;
    use HasFactory ;
    
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
        'images',

    ];

    public function ProductStock()
    {
        return $this->belongsTo(ProductStock::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
}
