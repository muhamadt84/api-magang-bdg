<?php

namespace App\Http\Controllers;

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
    
        $Comment = new Comment;
        $Comment->article_id= $validated['article_id'];
        $Comment->member_id = $validated['member_id'];
    
        $Comment->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Comment Berhasil Disimpan!',
            'data' => $Comment,
        ], 201);
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
