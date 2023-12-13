<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        게시글 작성
    </h2>
</x-slot>

<!-- Main Quill library -->
<script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Theme included stylesheets -->
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

@vite(['resources/js/createQuill.js', 'resources/css/quill.css'])

<div class="container md:mx-auto p-5 max-w-[1000px]">

    <form action="/post" method="POST" id="create-form">
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
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700">내용</label>
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
            <div id="editor">
                <div id="quill-editor"></div>
            </div>
        </div>

    </form>
    <div>
        <button
            class="py-2 px-4 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
            type="button"
            id="cancel-btn"
            >취소
        </button>
        <button
            class="py-2 px-4 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
            type="button"
            id="submit-btn"
            >저장
        </button>
    </div>
</div>

</x-app-layout>