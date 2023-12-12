<x-app-layout>
  <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Post') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto my-8 p-8 bg-white shadow-md rounded-md">
    <div class="text-2xl font-bold mb-4">제목</div>
    <div class="text-xl mb-4">{{$post->title}}</div>
    
    <div class="text-2xl font-bold mb-4">작성자</div>
    <div class="text-lg mb-4">{{$post->user->nickname}}</div>
    
    <div class="text-2xl font-bold mb-4">작성일</div>
    <div class="text-lg mb-4">{{$post->created_at}}</div>
    
    <div class="text-2xl font-bold mb-4">내용</div>
    <div class="mb-4">{{$post->content}}</div>
    
    <div class="flex space-x-4">
        <a href="/post/{{$post->id}}/edit" class="bg-blue-500 text-white px-4 py-2 rounded-md">수정</a>
        
        <form action="/post/{{$post->id}}" method="POST">
            @method("DELETE")
            @csrf
            <input class="bg-red-500 text-white px-4 py-2 rounded-md" type="submit" value="삭제">
        </form>
    </div>
</div>

</x-app-layout>