<?php

namespace App\Http\Controllers;

use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreArticleImageRequest;
use App\Http\Requests\UpdateArticleImageRequest;

class ArticleImageController extends Controller
{
    public function storeImage($image, $articleId)
    {
        if (!$articleId) {
            return null;
        }

        $articleImage = new ArticleImage;
        $articleImage->image = $image;
        $articleImage->article_id = $articleId;
        $articleImage->save();

        return $articleImage->image;
    }
}
