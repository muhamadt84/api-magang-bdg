<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
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
            $Product->makeHidden(['updated_at', 'deleted']);
            return response()->json([
                'success' => true,
                'message' => 'List Semua Product!',
                // 'current_page' => $posts->currentPage(),
                // 'per_page' => $posts->perPage(),
                // 'total_data' => $posts->total(),
                // 'last_page' => $posts->lastPage(),
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
        if ($request->file) {
            // Simpan file gambar melalui ProductImageController
            $imageController = new ProductImageController;
            $imagePath = $imageController->storeImage($request->file);
            $imagePath = $request->file('file')->store('public/images');
            // Dapatkan URL dari path gambar
            $imageLink = url(Storage::url($imagePath));
            $imageLink = '';
            $Product->image = $imageLink;
        }
    
        $Product->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Product Berhasil Disimpan!',
            'data' => $Product
        ], 201);
    }
    

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Product = Product::with('writer:id,username')->findOrFail($id);
        $Product->makeHidden(['updated_at', 'deleted_at']);
             if ($Product) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $Product
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
     * Show the form for editing the specified resource.
     */
    public function detail($id)
    {
        $Product = Product::findOrFail($id);
        $Product->makeHidden(['updated_at', 'deleted_at']);
             if ($Product) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $Product
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
            'nama' => 'required|max:255',
            'category_id' => 'required',
            'description' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'rating' => 'required',
            'brand' => 'required',
            'member_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find product by ID
        $Product = Product::find($id);
    
        // Check if product exists
        if (!$Product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    
        // Update the product fields
        $Product->nama = $Product->input('nama');
        $Product->category_id = $Product->input('category_id');
        $Product->description = $Product->input('description');
        $Product->price = $Product->input('price');
        $Product->discount = $Product->input('discount');
        $Product->rating = $Product->input('rating');
        $Product->brand = $Product->input('brand');
        $Product->member_id = $Product->input('member_id');

    
        if ($request->hasFile('image')) {
            // Simpan file gambar melalui ProductImageController
            $imageController = new ProductImageController;
            $imagePath = $imageController->storeImage($request->file('image'));
    
            // Dapatkan URL dari path gambar
            $imageLink = url(Storage::url($imagePath));
    
            $Product->image = $imageLink;
        }
    
        // Save the changes
        $Product->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Product Berhasil Diupdate!',
            'data' => $Product,
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
    
        if ($Product->deleted == 1) {
            $Product->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus secara permanen!',
                'data' => (object)[],
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
