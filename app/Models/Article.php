<?php

namespace App\Models;

use App\Models\ArticleImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'member_id',
        'categori_id',
        'total_like',
        'total_comment',
        'image'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function category()
    {
        return $this->belongsTo(TableCategory::class, 'categori_id');
    }

    public function image()
    {
        return $this->hasOne(ArticleImage::class);
    }
}
