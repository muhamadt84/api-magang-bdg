<?php

namespace App\Models;

use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $table = 'product_stocks';
    protected $fillable = [
        'id',
        'product_id',
        'qty',
        'flag'
    ];
}
