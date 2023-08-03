<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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
    
            $Comment = Comment::paginate($perPage);
            $Comment->makeHidden(['updated_at', 'deleted']);
            return response()->json([
                'success' => true,
                'message' => 'List Semua Comment!',
                // 'current_page' => $posts->currentPage(),
                // 'per_page' => $posts->perPage(),
                // 'total_data' => $posts->total(),
                // 'last_page' => $posts->lastPage(),
                'data' => $Comment,
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
            'article_id' => 'required',
            'comment' => 'required',
            'member_id' => 'required'
        ]);
    
        $Comment = new Comment;
        $Comment->article_id= $validated['article_id'];
        $Comment->comment= $validated['comment'];
        $Comment->member_id = $validated['member_id'];
    
        $Comment->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Comment Berhasil Disimpan!',
            'data' => $Comment,
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
        $Comment= Comment::with('writer:id,username')->findOrFail($id);
        $Comment->makeHidden(['updated_at', 'deleted_at']);
             if ($Comment) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $Comment
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


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'article_id' => 'required',
            'comment' => 'required',
            'member_id' => 'required'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find Comment by ID
        $Comment = Comment::find($id);
    
        // Check if Comment exists
        if (!$Comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment Tidak Ditemukan!',
                'data' => (object)[],    
            ], 404);
        }
    
        // Update the Comment fields
        $Comment->article_id= $validated['article_id'];
        $Comment->comment= $validated['comment'];
        $Comment->member_id = $validated['member_id'];
        $Comment->total_comment = 0;
        // Create a new article record in the 'article' table.
        $article = new Article(); // Assuming 'article' is the correct model name
        $article->total_comment= $total_comment;
        $article->save();
    
        // Save the changes
        $Comment->save();
        return response()->json([
            'success' => true,
            'message' => 'Comment Berhasil Diupdate!',
            'data' => $Comment,
        ], 200);
    } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Comment = Comment::find($id);
    
        if (!$Comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not Found !',
                'data' => (object)[],
            ], 404);
        }
    
        if ($Comment->deleted == 1) {
            $Comment->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Comment Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $Comment->deleted = 1;
            $Comment->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Comment Berhasil Dihapus!',
                'data' => $Comment,
            ], 200);
        }
    }
}