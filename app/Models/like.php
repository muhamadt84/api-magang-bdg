<?php

namespace App\Models;

use App\Models\like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class like extends Model
{
protected $fillable = [
        'article_id',
        'member_id',
        'created_at',

];
}
