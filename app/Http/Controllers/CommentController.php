<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->validate([
            'content' => ['required', 'string']
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->post_id = $request->post_id;

        $result = $comment->save();

        if ($result) {
            // return response()->json(['message' => 'Comment created successfully']);
            return redirect('/post/'.$comment->post_id);
        } else {
            return response()->json(['error' => 'Failed to create comment'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::findOrFail($id);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $comment->content = $request->content;
        $result = $comment->save();

        if ($result) {
            return response()->json(['message' => 'Comment updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update comment'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $post = $comment->post;

        $result = $comment->delete();

        if ($result) {
            // return view('boards.show', ['post' => $post]);
            return response()->json(['message' => 'Comment deleted successfully']);
            // return redirect('/post/'.$comment->post_id); // post도 삭제가 되버리네
        } else {
            return response()->json(['error' => 'Failed to delete comment'], 500);
        }
    }
}
