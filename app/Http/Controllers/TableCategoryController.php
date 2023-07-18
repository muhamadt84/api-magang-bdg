<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\TableCategory;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTableCategoryRequest;
use App\Http\Requests\UpdateTableCategoryRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    
            $tableCategory = TableCategory::paginate($perPage);
            $tableCategory->makeHidden(['updated_at', 'deleted','deleted_at']);
            return response()->json([
                'success' => true,
                'message' => 'List Semua Posts!',
                // 'current_page' => $posts->currentPage(),
                // 'per_page' => $posts->perPage(),
                // 'total_data' => $posts->total(),
                // 'last_page' => $posts->lastPage(),
                'data' => $tableCategory->items(),
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $tableCategory = new TableCategory;
        $tableCategory->name = $request->input('name');
        // Setel nilai atribut lainnya jika ada
    
        $tableCategory->save();
        return response()->json([
            'success' => true,
            'message' => 'Kategori Berhasil Disimpan!',
            'data' => $tableCategory,
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
        $tableCategory = TableCategory::with('writer:id,username')->findOrFail($id);
        $tableCategory->makeHidden(['updated_at', 'deleted_at',]);
             if ($tableCategory) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $tableCategory
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
    public function edit(TableCategory $tableCategory)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, $id)
{
    // Define validation rules
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], 422);
    }

    // Find post by ID
    $tableCategory = TableCategory::find($id);

    // Check if post exists
    if (!$tableCategory) {
        return response()->json([
            'success' => false,
            'message' => 'Category Tidak Ditemukan!',
            'data' => (object)[],
        ], 404);
    }

    // Update the category name
    $tableCategory->name = $request->input('name');

    // Save the changes
    $tableCategory->save();

    return response()->json([
        'success' => true,
        'message' => 'Category Berhasil Diupdate!',
        'data' => $tableCategory
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tableCategory = TableCategory::find($id);
    
        if (!$tableCategory) {
            return response()->json([
                'success' => false,
                'message' => 'Category not Found !',
                'data' => (object)[],
            ], 404);
        }
    
        if ($tableCategory->deleted == 1) {
            $tableCategory->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Category Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $tableCategory->deleted = 1;
            $tableCategory->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Category Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }
}
