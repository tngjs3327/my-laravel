<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('posts.create');
    }

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

            // 성공 응답 반환
            return response()->json(['msg' => 'success']);
        } catch(Exception $e){
            // 실패 응답 반환
            return response()->json(['msg' => 'failed', 'error' => $e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        $post = Post::find($id);
        return view('posts.show', ['post' => $post]);
    }

    public function edit(string $id)
    {
        $post = Post::find($id);
        return view('posts.updateForm', ['post' => $post]);
    }

    public function update(Request $request, string $id)
    {
        // 유효성 검사
        $request->validate([
            'title' => 'required|string|max:255',
            'context' => 'required',
        ]);
    
        try {
            // 모델 찾기
            $post = Post::findOrFail($id);
    
            // 필드 업데이트
            $post->update([
                'title' => $request->title,
                'content' => $request->context,
            ]);
    
            // 성공 응답 반환
            return redirect('/post/'.$id);
        } catch (Exception $e) {
            // 실패 응답 반환
            return response()->json(['msg' => 'failed', 'error' => $e->getMessage()]);
        }
    }
    

    public function destroy(string $id)
    {
        Post::destroy($id);

        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }

}
