<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    // store comment for a post
    public function store($postId, Request $request)
    {
        // validate user_id and comment
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|exists:users,id',
            'comment'   => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // find post
            $post = Post::findOrFail($postId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }

        // create comment
        $comment = $post->comments()->create([
            'user_id' => $request->user_id,
            'comment' => $request->comment
        ]);

        $comment->load(['post.user', 'user']); // load post and user

        // send notification to post owner
        $postOwner = $comment->post->user;
        Notification::send($postOwner, new CommentNotification($comment));

        return response()->json([
            'message'   => 'Comment added successfully',
            'post_id'   => $comment
        ], 200);
    }

    // list comments post
    public function index($postId)
    {
        try {
            $post = Post::with('comments')->findOrFail($postId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found or invalid post_id.'
            ], 404);
        }

        return response()->json([
            'post_id' => $post->id,
            'comments' => $post->comments
        ], 200);
    }
}
