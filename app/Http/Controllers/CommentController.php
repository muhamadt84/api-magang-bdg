<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Http\Controllers\CommentController;

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
                'data' => $Comment->items(),
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //create post
        $post = Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);

        //return response
        return new Comment(true, 'Data Post Berhasil Ditambahkan!', $post);
    }
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
                'data' => (object)[],
            ], 200);
        }
    }
}
