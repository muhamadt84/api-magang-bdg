<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;
    protected $table = 'table_article_image'; // Nama tabel di database
    
    protected $fillable = [
        'article_id',
        'path',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
