<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Notifications\PostLikeNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class LikeController extends Controller
{
    // like or unlike post
    public function likeUnlike($id, Request $request)
    {
        try {
            // Validate user_id exists in users table
            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Invalid user_id.',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $post = Post::with('likes')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found or invalid post_id.'
            ], 404);
        }

        $user_id = $request->user_id;

        if ($post->user_id == $user_id) {
            return response()->json([
                'message' => 'You cannot like your own post.'
            ], 422);
        }

        $like = $post->likes->where('user_id', $user_id)->first();

        if ($like) {
            
            $like->delete();
            $post->load('likes');

            return response()->json([
                'message'     => 'Post unliked successfully',
                'post_id'     => $post->id,
                'likes_count' => $post->likes->count()
            ], 200);

        } else {

            $post->likes()->create(['user_id' => $user_id]);
            $post->load('likes');

            // send notification to post owner
            $postOwner = $post->user;
            Notification::send($postOwner, new PostLikeNotification($post, $user_id));


            return response()->json([
                'message'     => 'Post liked successfully',
                'post_id'     => $post->id,
                'likes_count' => $post->likes->count()
            ], 200);
        }
    }
}
