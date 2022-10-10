<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth'])->only(['store', 'destroy']);
    }

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->with('user', 'likes')->paginate(20);
        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function store(Request $request)
    {
        // validation
        $this->validate($request, [
            'body' => 'required',
        ]);

        // store post
        $request->user()->posts()->create($request->only('body'));

        // redirect
        return back();
    }

    public function destroy(Post $post, Request $request)
    {
        // check if the user is the owner of the post
        $this->authorize('delete', $post);

        // delete the post
        $post->delete();

        // redirect
        return back();
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }
}
