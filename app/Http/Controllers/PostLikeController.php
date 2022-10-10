<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Mail\PostLiked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostLikeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function store(Post $post, Request $request)
    {

        // if the user has already liked the post
        if ($post->likedPost($request->user())) {
            return response(null, 409);
        }

        // Create a like for the current user and the current post
        $post->likes()->create([
            'user_id' => $request->user()->id,
        ]);

        // Send an email to the post owner

        if (!$post->likes()->onlyTrashed()->where('user_id', $request->user()->id)->count()) {
            Mail::to($post->user)->send(new PostLiked(auth()->user(), $post));
        }




        // Redirect back
        return back();
    }

    public function destroy(Post $post, Request $request)
    {
        // delete the like
        $request->user()->likes()->where('post_id', $post->id)->delete();

        // Redirect back
        return back();
    }
}
