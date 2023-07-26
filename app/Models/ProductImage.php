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

    protected $fillable = ['product_id','image'];


        // Define the inverse relationship with Article model (one-to-one)
public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
