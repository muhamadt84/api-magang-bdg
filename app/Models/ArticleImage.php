<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleImage extends Model
{
    protected $table = 'table_article_image'; // Specify the correct table name here

    protected $fillable = ['image', 'article_id'];

    // Define the inverse relationship with Article model (one-to-one)
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}