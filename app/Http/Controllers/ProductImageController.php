<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\ProductImageController;

class ProductImageController extends Controller
{
    public function storeImage(UploadedFile $file)
        {
            $request = new Request(['image' => $file]);
            $validator = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            $imagePath = $file->store('public/images');
    
            // Dapatkan URL dari path gambar
            $imageLink = url(Storage::url($imagePath));
    
            // Simpan informasi gambar ke tabel 'table_article_image'
            $productimage = new ProductImage;
            $productimage->image_path = $imagePath;
            $productimage->image_link = $imageLink;
            $productimage->save();
    
            return $productimage->image_path;
        }
}
