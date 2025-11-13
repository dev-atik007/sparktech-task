<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCreateNotification;
use Illuminate\Support\Facades\Notification;



class PostController extends Controller
{
    public function store(Request $request)
    {
        // validation 
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create post
        $post = Post::create([
            'user_id' => $request->user_id,
            'title'   => $request->title,
            'content' => $request->content
        ]);

        $post->load('user'); // load user relation

        // send notification to all users
        $users = User::where('id', '!=', $request->user_id)->get();
        Notification::send($users, new PostCreateNotification($post));

        return response()->json([
            'message' => 'Post created successfully & notifications send!',
            'post'    => $post
        ], 200);
    }


    // list posts with likes and comments count
    public function index()
    {
        $posts = Post::withCount('likes', 'comments')->get();
        return response()->json($posts, 200);
    }

    
}
