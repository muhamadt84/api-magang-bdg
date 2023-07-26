<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('limit', 10); // Menentukan jumlah item per halaman, defaultnya 10
        
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'integer|min:1|max:100' // Validasi input limit
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors()
                ], 400);
            }
            
            $Product = Product::paginate($perPage);
            $Product->makeHidden(['updated_at', 'deleted','deleted_at']);
            return response()->json([
                'success' => true,
                'message' => 'List Semua Product!',
                'data' => $Product,
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    
    
public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'rating' => 'required',
            'brand' => 'required',
            'member_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $Product = new Product;
        $Product->name = $validated['name'];
        $Product->category_id = $validated['category_id'];
        $Product->description = $validated['description'];
        $Product->price = $validated['price'];
        $Product->discount = $validated['discount'];
        $Product->rating = $validated['rating'];
        $Product->brand = $validated['brand'];
        $Product->member_id = $validated['member_id'];
    
        $Product->save();

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imagePath = $image->store('public/images');
    
                // Create an ProductImage model to associate the image with the product
                $ProductImage = new ProductImage;
                $ProductImage->image = $imagePath;
    
                // Save the product image with the product relationship
                $Product->images()->save($ProductImage);
            }
        }
        // $Product->loadMissing('image');
        $Product->makeHidden(['updated_at', 'deleted_at']);
        return response()->json([
            'success' => true,
            'message' => 'Product Berhasil Disimpan!',
            'data' => $Product->loadMissing('image'),
        ], 201);
    }
    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */

     /**
     * Show the form for editing the specified resource.
     */
    public function detail(Request $request)
    {
        $Product = Product::findOrFail($request);
        $Product->makeHidden(['updated_at', 'deleted_at']);
             if ($Product) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Product!',
                'data'    => $Product->loadMissing('image'),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Tidak Ditemukan!',
                'data' => (object)[],
            ], 401);
        }
    }

    
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Define validation rules
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|max:255',
        'category_id' => 'sometimes|required',
        'description' => 'sometimes|required',
        'price' => 'sometimes|required',
        'discount' => 'sometimes|required',
        'rating' => 'sometimes|required',
        'brand' => 'sometimes|required',
        'member_id' => 'sometimes|required',
        'image' => 'image|sometimes|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], 422);
    }

    // Find Product by ID
    $Product = Product::find($id);

    // Check if Product exists
    if (!$Product) {
        return response()->json([
            'success' => false,
            'message' => 'Product Tidak Ditemukan!',
            'data' => (object)[],
        ], 404);
    }

    // Update the Product fields
    $Product->name = $request->input('name');
    $Product->category_id = $request->input('category_id');
    $Product->description = $request->input('description');
    $Product->price = $request->input('price');
    $Product->discount = $request->input('discount');
    $Product->rating = $request->input('rating');
    $Product->brand = $request->input('brand');
    $Product->member_id = $request->input('member_id');

    // Save the changes
    $Product->save();

    // Handle the image upload
    if ($request->hasFile('image')) {
        $images = $request->file('image');

        // Delete existing images (optional, if you want to replace all images)
        $Product->images()->delete();

        // Upload and save the new images
        foreach ($images as $image) {
            $imagePath = $image->store('public/images');

            // Create an ArticleImage model to associate the image with the article
            $ProductImage = new ProductImage;
            $ProductImage->image = $imagePath;

            // Associate the image with the article
            $Product->images()->save($ProductImage);
        }
    }

    // Load the missing image relationship if it exists
    $Product->loadMissing('image');

    // Make hidden any attributes you want to exclude from the JSON response
    $Product->makeHidden(['updated_at', 'deleted_at']);
    $Product->image->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    return response()->json([
        'success' => true,
        'message' => 'Product Berhasil Diupdate!',
        'data' => $Product,
        'image_url' => $imageUrl ?? null, // Add image_url only if an image was uploaded
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Product = Product::find($id);
    
        if (!$Product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not Found !',
                'data' => (object)[],
            ], 404);
        }
    
        if ($Product->deleted_at) {
            $Product->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $Product->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }
}
