<x-app-layout>
<div class="container mx-auto p-5">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            게시글 작성
        </h2>
  </x-slot>
    <form action="/post" method="POST">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">제목</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                class="mt-1 p-2 w-full border rounded-md"
                placeholder="제목을 입력하세요"
            />
            @error('title')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700">내용</label>
            <textarea
                id="content"
                name="content"
                class="mt-1 p-2 w-full border rounded-md"
                rows="4"
                placeholder="내용을 입력하세요">{{ old('content') }}</textarea>
            @error('content')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button
            class="py-2 px-4 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
            type="submit">저장
        </button>
    </form>
</div>

</x-app-layout>