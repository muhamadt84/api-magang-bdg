<?php

namespace App\Models;

use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product_image extends Model
{
    use HasFactory;
    protected $table = 'table_product_image';
    protected $fillable = ['image_path', 'image_link'];
}
