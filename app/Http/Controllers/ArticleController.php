<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleImage;
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
            $article->makeHidden(['updated_at', 'deleted','deleted_at']);
            return response()->json([
                'success' => true,
                'message' => 'List Semua Article!',
                'data' => $article->loadMissing('images'),
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
            'title' => 'required|max:255',
            'description' => 'required',
            'member_id' => 'required',
            'categori_id' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $article = new Article;
        $article->title = $validated['title'];
        $article->description = $validated['description'];
        $article->member_id = $validated['member_id'];
        $article->categori_id = $validated['categori_id'];
        $article->total_like = 0;
        $article->total_comment = 0;
    
        $article->save();
    
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imagePath = $image->store('public/images');
    
                // Create an ArticleImage model to associate the image with the article
                $articleImage = new ArticleImage;
                $articleImage->image = $imagePath;
    
                // Save the article image with the article relationship
                $article->images()->save($articleImage);
            }
        }
    
        // Hide 'updated_at' and 'deleted_at' columns
        $article->makeHidden(['updated_at', 'deleted_at']);
    
        return response()->json([
            'success' => true,
            'message' => 'Artikel Berhasil Disimpan!',
            'data' => $article->loadMissing('images'),
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
    public function detail($id)
    {
        $article = Article::findOrFail($id);
        $article->makeHidden(['updated_at', 'deleted_at']);
        $article->images->makeHidden(['created_at', 'updated_at', 'deleted_at']);
             if ($article) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Article!',
                'data'    => $article->loadMissing('images'),
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
    public function update(Request $request, string $id)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'member_id' => 'sometimes|required',
        'first_name' => 'sometimes|required',
        'last_name' => 'sometimes|required',
        'dob' => 'sometimes|required',
        'gender' => 'sometimes|required',
        'address' => 'sometimes|required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
        'bio' => 'sometimes|required',
        'highschool' => 'sometimes|required',
        'phone_number' => 'sometimes|required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Find the member detail by id
    $memberDetail = MembersDetail::find($id);

    if (!$memberDetail) {
        return response()->json([
            'success' => false,
            'message' => 'Member not found',
        ], 404);
    }

    // Pastikan nama kolom yang digunakan sesuai dengan struktur tabel dan modelnya
    $dataToUpdate = $request->only([
        'member_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'address',
        'bio',
        'high_school', // Fix typo here: 'higschool' should be 'highschool'
        'phone_number',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('images', 'public');
        $dataToUpdate['image'] = $imagePath;
    }

    // Periksa apakah nilai atribut tidak kosong sebelum mengisinya ke dalam model
    foreach ($dataToUpdate as $key => $value) {
        if ($request->filled($key)) {
            $memberDetail->$key = $value;
        }
    }

    if ($request->has('password')) {
        $memberDetail->password = bcrypt($request->input('password'));
    }

    $memberDetail->save();

    return response()->json([
        'success' => true,
        'message' => 'Member updated successfully',
        'member' => $memberDetail,
    ]);
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
    
        if ($article->deleted_at) {
            $article->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Article Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $article->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Article Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }
}
