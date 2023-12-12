<x-app-layout>
  <div class="container mx-auto p-5">
    <h1 class="text-3xl font-bold mb-5">게시글 수정</h1>

    <form action="/post/{{ $post->id }}" method="POST">
        @method("PUT")
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">제목</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ $post->title }}"
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
                placeholder="내용을 입력하세요">{{ $post->content }}</textarea>
            @error('content')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <input
            class="py-2 px-4 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
            type="submit"
            value="저장"
        />
    </form>
</div>

</x-app-layout>