<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
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
    
            $article = Article::paginate($perPage);
            $article->makeHidden(['updated_at', 'deleted']);
            return response()->json([
                'success' => true,
                'message' => 'List Semua Article!',
                // 'current_page' => $posts->currentPage(),
                // 'per_page' => $posts->perPage(),
                // 'total_data' => $posts->total(),
                // 'last_page' => $posts->lastPage(),
                'data' => $article->items(),
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
            'title' => 'required|max:255',
            'description' => 'required',
            'member_id' => 'required',
            'categori_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $article = new Article;
        $article->title = $validated['title'];
        $article->description = $validated['description'];
        $article->member_id = $validated['member_id'];
        $article->categori_id = $validated['categori_id'];
        $article->total_like = 0; // Set nilai awal total_like menjadi 0
        $article->total_comment = 0; // Set nilai awal total_comments menjadi 0
        // if ($request->file) {
        //     // Simpan file gambar melalui ArticleImageController
        //     $imageController = new ArticleImageController;
        //     $imagePath = $imageController->storeImage($request->file);
        //     $imagePath = $request->file('file')->store('public/images');
        //     // Dapatkan URL dari path gambar
        //     $imageLink = url(Storage::url($imagePath));
        //     $imageLink = '';
        //     $article->image = $imageLink;
        // }
    
        $article->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Artikel Berhasil Disimpan!',
            'data' => $article,
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
        $article = Article::with('writer:id,username')->findOrFail($id);
        $article->makeHidden(['updated_at', 'deleted_at']);
             if ($article) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $article
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
        $article = Article::findOrFail($id);
        $article->makeHidden(['updated_at', 'deleted_at']);
             if ($article) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $article
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
            'title' => 'required|max:255',
            'description' => 'required',
            'member_id' => 'required',
            'categori_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find article by ID
        $article = Article::find($id);
    
        // Check if article exists
        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    
        // Update the article fields
        $article->title = $request->input('title');
        $article->description = $request->input('description');
        $article->member_id = $request->input('member_id');
        $article->categori_id = $request->input('categori_id');
    
        // if ($request->hasFile('image')) {
        //     // Simpan file gambar melalui ArticleImageController
        //     $imageController = new ArticleImageController;
        //     $imagePath = $imageController->storeImage($request->file('image'));
    
        //     // Dapatkan URL dari path gambar
        //     $imageLink = url(Storage::url($imagePath));
    
        //     $article->image = $imageLink;
        // }
    
        // Save the changes
        $article->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Artikel Berhasil Diupdate!',
            'data' => $article,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article = Article::find($id);
    
        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not Found !',
                'data' => (object)[],
            ], 404);
        }
    
        if ($article->deleted == 1) {
            $article->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Article Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $article->deleted = 1;
            $article->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Article Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }
}
