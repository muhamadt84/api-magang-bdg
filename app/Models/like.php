<?php

namespace App\Models;

use App\Models\like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class like extends Model
{

     use HasFactory;

     protected $table = 'likes';
     protected $fillable = [
        'article_id',
        'member_id'

];
}
