<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'table_product';
    protected $fillable 
     = [
        'name',
        'category_id',
        'description',
        'price',
        'discount',
        'rating',
        'brand',
        'member_id',
        'image'

       
    ];

    
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