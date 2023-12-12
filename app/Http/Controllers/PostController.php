<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required']
        ]);

        try{
            $post = new post();
            $post->title = $inputs['title'];
            $post->content = $inputs['content'];
            $post->user_id = Auth::id();

            $post->save();

            return redirect('/post');
        } catch(Exception $e){
            dd($e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);
        return view('posts.update', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = post::find($id);

        $inputs = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required']
        ]);


        $post->title = $inputs['title'];
        $post->content = $inputs['content'];
        $post->save();

        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Post::destroy($id);

        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }
}
