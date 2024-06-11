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
    {{-- <script src="/resources/js/createQuill.js" ></script>
    <link href="/resources/css/quill.css" rel="stylesheet"> --}}
    @vite(['resources/js/createQuill.js', 'resources/css/quill.css'])
    <link rel="stylesheet" href="{{ asset('resources/css/quill.css') }}">

        <script src="{{ asset('resources/js/createQuill.js') }}" defer></script>
    
    <div class="container md:mx-auto p-8 max-w-[1000px] bg-white shadow-lg rounded-lg">
        <form action="/post" method="POST" id="create-form" class="space-y-6">
            @csrf
            <div>
                <label for="title" class="block text-lg font-medium text-gray-700">제목</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    class="mt-2 p-3 w-full border rounded-md text-gray-700"
                    placeholder="제목을 입력하세요"
                />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
    
            <div>
                <label for="content" class="block text-lg font-medium text-gray-700">내용</label>
                <div id="editor" class="mt-2 border rounded-md">
                    <div id="quill-editor"></div>
                </div>
            </div>
            <input type="hidden" name='context' id="context-create">
            <x-input-error :messages="$errors->get('context')" class="mt-2" />
    
            <div class="flex justify-end space-x-4">
                <button
                    class="py-2 px-4 bg-gray-500 text-white rounded-md text-sm hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray active:bg-gray-800"
                    type="button"
                    id="cancel-btn"
                    >취소
                </button>
                <button
                    class="py-2 px-4 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
                    type="submit"
                    id="submit-btn-create"
                    >저장
                </button>
            </div>
        </form>
    </div>
    


</x-app-layout>