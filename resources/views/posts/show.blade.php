<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Post') }}
        </h2>
    </x-slot>

    {{-- <script src="//cdn.quilljs.com/1.3.6/quill.js"></script> --}}
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @vite(['resources/js/showQuill.js', 'resources/css/quill.css'])

    <div class="max-w-[900px] mx-auto my-10 p-8 bg-white shadow-2xl rounded-lg border border-gray-200">
        <div class="hidden" id="post-data" data-post="{{ json_encode($post) }}"></div>
        
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h1 class="text-2xl font-semibold mb-2 text-gray-900">제목</h1>
            <p class="text-xl text-gray-700">{{ $post->title }}</p>
        </div>
        
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-2xl font-semibold mb-2 text-gray-900">작성자</h2>
            <p class="text-lg text-gray-600">{{ $post->user->nickname }}</p>
        </div>
        
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-2xl font-semibold mb-2 text-gray-900">작성일</h2>
            <p class="text-lg text-gray-600">{{ $post->created_at }}</p>
        </div>
        
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-900">내용</h2>
            <div id="editor" class="prose prose-sm prose-blue">
                <div id="quill-editor"></div>
            </div>
        </div>
    
        @if(Auth::check() && Auth::user()->id == $post->user_id)
            <div class="flex space-x-4">
                <a href="/post/{{ $post->id }}/edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">수정</a>
                <form action="/post/{{ $post->id }}" method="POST">
                    @method("DELETE")
                    @csrf
                    <input class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" type="submit" value="삭제">
                </form>
            </div>
        @endif
    </div>
    
    
    
    
    
<!-- 댓글 -->
<div class="items-center">
    <div class="flex items-center justify-between border-t border-b max-w-[900px] mx-auto my-10 p-5 bg-white shadow-lg rounded-lg">
        <!-- 댓글 작성 폼 -->
        <form class="flex items-center w-full" action="/post/{{ $post->id }}/comment" method="POST">
            @csrf
            <label class="text-center w-20 text-lg font-bold text-gray-800">댓글</label>
            <textarea class="resize-none min-h-14 w-full border rounded-md p-2 text-gray-700" name="content" placeholder="댓글을 등록하세요.">{{ old('content') }}</textarea>
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="submit" class="w-20 h-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-3" value="Add">
            <x-input-error :messages="$errors->get('content')" class="my-2 justify-center flex" />
        </form>
    </div>
    

    <!-- HTML 구조 -->
    <div class="context_comments flex-col items-start max-h-screen h-full w-full">
        @if ($post->comments()->count() == 0)
            <!-- 댓글이 없을 때 -->
            <div class="no_comt flex justify-center items-center h-20 my-10">
                <div class="no_comt_box border items-center justify-center rounded-lg text-gray-400 bg-white w-80 h-full flex shadow-lg">
                    등록된 댓글이 없습니다
                </div>
            </div>            
        @else
            <!-- 댓글이 있을 때 -->
            @foreach ($post->comments()->orderByDesc('created_at')->get() as $comment)
                <div id="contextComments" class="flex items-center justify-center mx-auto min-h-20 mt-4 flex-row max-w-[800px] p-3 space-x-4 bg-white shadow-lg rounded-lg border border-gray-200">
                    <div class="comt_icon_box flex flex-col justify-center items-center me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-xs text-gray-700">{{ $comment->user->nickname }}</span>
                    </div>
                    <div class="resi comment_content w-[530px] min-h-20 border-l pl-2 p-2 text-gray-700" id="{{ "comment-content".$comment->id }}">
                        {{ $comment->content }}
                    </div>
                    <div class="comment_date flex flex-col justify-center items-center w-24 px-2 border-l text-gray-600">
                        <span class="text-xs">{{ explode(' ', $comment->created_at)[0] }}</span>
                        <span class="text-xs">{{ explode(' ', $comment->created_at)[1] }}</span>
                    </div>
                    <div class="comment_btnBox flex items-center m-auto w-76 pl-4 border-l">
                        <div class="flex items-center justify-center">
                            <div x-data="{ open: true }" class="relative inline-block text-left">
                                @if(Auth::check() && Auth::user()->id == $comment->user_id)
                                    <div @click="open = !open" class="inline-flex justify-center w-full px-2 py-2 text-sm font-medium border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 0100 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>
                                    </div>
                                @endif
                                <div x-show="!open" @click.away="!open = false" class="z-10 origin-top-left absolute text-center left-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 px-2 py-2">
                                    <button class="edit-btn block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" data-id="{{ $comment->id }}">수정</button>
                                    <button class="delete-btn block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" data-id="{{ $comment->id }}">삭제</button>
                                    <button class="save-btn hidden px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" data-id="{{ $comment->id }}">저장</button>
                                    <button class="cancel-btn hidden px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md" data-id="{{ $comment->id }}">취소</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

    
    

            
</x-app-layout>