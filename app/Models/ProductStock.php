<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_stock extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'id',
        'product_id',
        'qty' 
    ];
}
