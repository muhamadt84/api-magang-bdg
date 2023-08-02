<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProductStockController;

class ProductStockController extends Controller
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
    
            $ProductStock = ProductStock::paginate($perPage);
            return response()->json([
                'success' => true,
                'message' => 'List Semua ProductStock!',
                'data' => $ProductStock,
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
        $validated = $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
        ]);
    
        $ProductStock = new ProductStock;
        $ProductStock->product_id = $validated['product_id'];
        $ProductStock->qty = $validated['qty'];
        
    
        $ProductStock->save();
        return response()->json([
            'success' => true,
            'message' => 'ProductStock Berhasil Disimpan!',
            'data' => $ProductStock,
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
    public function show(Request $request)
    {
        $ProductStock = ProductStock::with('writer:id,username')->findOrFail($request);
        $ProductStock->makeHidden(['updated_at', 'deleted_at']);
             if ($ProductStock) {
            return response()->json([
                'success' => true,
                'message' => 'List ProductStock!',
                'data'    => $ProductStock,
            ], 200);
            
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ProductStock Tidak Ditemukan!',
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
            'qty' => 'required',
            'product_id' => 'required',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find article by ID
        $Product = ProductStock::find($id);
    
        // Check if article exists
        if (!$Product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    
        // Update the article fields
        $Product->product_id = $validated['product_id'];
        $Product->qty = $validated['qty'];
    
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
        $Product = ProductStock::find($id);
    
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
            ], 2020);
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
