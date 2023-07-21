<?php

namespace App\Models;

use App\Models\product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'table_product_image';
    protected $fillable = ['image_path', 'image_link'];
}
