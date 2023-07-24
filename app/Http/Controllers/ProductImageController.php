<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProductImageController;

class ProductImageController extends Controller
{
    public function create(UploadedFile $file)
        {
            $request = new Request(['image' => $file]);
            $validator = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            $imagePath = $file->store('public/images');
    
            // Dapatkan URL dari path gambar
            $imageLink = url(Storage::url($imagePath));
    
            // Simpan informasi gambar ke tabel 'product_images'
            $productimage = new ProductImage;
            $productimage->image_path = $imagePath;
            $productimage->image_link = $imageLink;
            $productimage->save();
    
            return $productimage->image_path;
        }
}
