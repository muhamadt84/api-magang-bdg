<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LikeController;

class LikeController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required',
            'member_id' => 'required'
        ]);
    
        $Like= new Like;
        $Like->article_id= $validated['article_id'];
        $Like->member_id = $validated['member_id'];
    
        $Like->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Like Berhasil Disimpan!',
            'data' => $Like,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'article_id' => 'required',
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
        $Like = Like::find($id);
    
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
        $Comment->member_id = $validated['member_id'];
        $Comment->total_like = 0;

    
        // Save the changes
        $Comment->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Comment Berhasil Diupdate!',
            'data' => $Comment,
        ], 200);
    }
    public function destroy($id)
    {
        $Like = Like::find($id);
    
        if (!$Like) {
            return response()->json([
                'success' => false,
                'message' => 'Like not Found !',
                'data' => (object)[],
            ], 404);
        }
    
        if ($Like->deleted == 1) {
            $Like->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Like Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $Like->deleted = 1;
            $Like->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Like Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }


}
