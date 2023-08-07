<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
       $perPage = $request->query('limit', 10); // Menentukan jumlah ite m per halaman, defaultnya 10
        
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
                'data' => $Product->loadMissing(['ProductStock', 'images']),
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    
    
public function add(Request $request)
    {
        try {
            $validated = $request->validate([
            'name' => 'required|max:255',
            'categori_id' => 'required|exists:table_categories,id', // Check if categori_id exists in the 'table_categories' table
            'description' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'rating' => 'required',
            'brand' => 'required',
            'member_id' => 'required|exists:table_member,id', // Check if member_id exists in the 'table_member' table
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            ]);
    
        $Product = new Product;
        $Product->name = $validated['name'];
        $Product->categori_id = $validated['categori_id'];
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
    
                    // Assuming you have symlink set up for storage folder
                    // Get the public URL of the stored image
                    $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath));
    
                    // Create an ProductImage model to associate the image with the article
                    $ProductImage = new ProductImage;
                    $ProductImage->image = $imageUrl;
    
                    // Save the Product image with the article relationship
                    $ProductImage->images()->save($ProductImage);
                }
            }
    
            // Hide 'updated_at' and 'deleted_at' columns
            $Product->makeHidden(['updated_at', 'deleted_at']);
    
            return response()->json([
                'success' => true,
                'message' => 'Artikel Berhasil Disimpan!',
                'data' => $Product->loadMissing('images'),
            ], 201);
        } catch (ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validationException->errors(),
            ], 422);
        } catch (ModelNotFoundException $notFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'errors' => [
                    'member_id' => ['Member ID atau Category ID tidak ditemukan'],
                    'categori_id' => ['Member ID atau Category ID tidak ditemukan'],
                ],
            ], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */

     /**
     * Get the details of a specific member.
     */
    public function detail($id)
    {
        try {
            $Product = Product::findOrFail($id);
            $Product->makeHidden(['updated_at', 'deleted_at']);
            $Product->images->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    
            return response()->json([
                'success' => true,
                'message' => 'Detail Product!',
                'data'    => $Product->loadMissing('images'),
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
        // Define validation rules
        $validator = Validator::make($request->all(), [
        'categori_id' => 'sometimes|required|exists:table_categories,id',
        'description' => 'sometimes|required',
        'price' => 'sometimes|required',
        'discount' => 'sometimes|required',
        'rating' => 'sometimes|required',
        'brand' => 'sometimes|required',
        'member_id' => 'sometimes|required|exists:table_member,id',
        'image.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find Product by ID
        $Product = Article::find($id);
    
        // Check if Product exists
        if (!$Product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    
        $Product->fill($request->only([  
        // 'name', 'categori_id', 'description', 'price', 'discount', 'rating', 'brand', 'member_id', 'image'
        'name', 'categori_id', 'description'
        ]));
    
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
                $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath));
                // Create an ProductImage model to associate the image with the Product
                $ProductImage = new ProductImage;
                $ProductImage->image = $imageUrl;
    
                // Associate the image with the Product
                $Product->images()->save($ProductImage);
            }
        }
    
        // Load the missing image relationship if it exists
        $Product->loadMissing('images');
    
        // Make hidden any attributes you want to exclude from the JSON response
        $Product->makeHidden(['updated_at', 'deleted_at']);
        $Product->images->makeHidden(['created_at', 'updated_at', 'deleted_at']); // Use 'images' not 'image'
        return response()->json([
            'success' => true,
            'message' => 'Artikel Berhasil Diupdate!',
            'data' => $Product->loadMissing('images'),
        ], 200);
    } catch (ValidationException $validationException) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi Gagal',
            'errors' => $validationException->errors(),
        ], 422);
    } catch (ModelNotFoundException $notFoundException) {
        return response()->json([
            'success' => false,
            'message' => 'Data Tidak Ditemukan',
            'errors' => [
                'member_id' => ['Member ID atau Category ID tidak ditemukan'],
                'categori_id' => ['Member ID atau Category ID tidak ditemukan'],
            ],
        ], 404);
    }
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
    
        if ($Product->deleted == 1) {
            $Product->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus secara permanen!',
                'data' =>  $Product,
            ], 200);
        } else {
            $Product->deleted = 1;
            $Product->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }
}