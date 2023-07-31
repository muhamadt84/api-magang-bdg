<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProductImageController;
use App\Http\Requests\StoreProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;

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
    
            // Simpan informasi gambar ke tabel 'product_images'
            $ProductImage = new ProductImage;
            $ProductImage->image_path = $imagePath;
            $ProductImage->image_link = $imageLink;
            $ProductImage->save();
    
            return $ProductImage->image_path;
        }
}
