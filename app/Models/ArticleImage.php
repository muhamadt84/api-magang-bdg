<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleImage extends Model
{
    use SoftDeletes;

    protected $table = 'table_article_image';

    protected $fillable = [
        'article_id',
        'image',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}