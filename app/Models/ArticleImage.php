<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;
    protected $table = 'table_article_image';
    protected $fillable = ['image_path', 'image_link'];
}
