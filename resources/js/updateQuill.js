import Quill from 'quill';
import ImageResize from 'quill-image-resize';

let sumImageList = [];

Quill.register('modules/ImageResize', ImageResize);

let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

let toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // 굵게, 기울이기, 밑줄, 가운데 줄
    ['blockquote'],                                   // 인용문

    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],         // 내어쓰기 / 들여쓰기
    [{ 'direction': 'rtl' }],                         // 글 시작지점(좌, 우)

    [{ 'size': ['small', false, 'large', 'huge'] }],  // 글자크기
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],        // header, h태그

    [{ 'color': [] }, { 'background': [] }],          // 글자색, 글자배겨액
    [{ 'font': [] }],                                 // 폰트
    [{ 'align': [] }],                                // 정렬
    ['link', 'image'],

    ['clean']
]

let editor = new Quill('#quill-editor',  {
    theme: 'snow', // 또는 'bubble'을 선택할 수 있습니다.
    placeholder: '내용을 입력하세요...',
    modules: {
        toolbar: {
            container : toolbarOptions,
            handlers: { image: imageHandler },
        },
        ImageResize: {parchment: Quill.import('parchment')},
    },
});

let postDataElement = document.querySelector('#post-data');
let postData = JSON.parse(postDataElement.dataset.post);
let jsonString = postData.content;
let content = JSON.parse(jsonString);

editor.setContents(content);
sumImageList = extractionValue(editor.getContents());

function imageHandler() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch('/uploadImage', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();

            if (!data.url) {
                throw new Error('Invalid response format');
            }

            let range = editor.getSelection(true);
            editor.insertEmbed(range.index, 'image', data.url);
            sumImageList.push(data.path);
        } catch (error) {
            console.error('이미지 업로드 오류:', error);
        }
    };
};

// 이미지 삭제 (fileList, Set타입 리스트, delete or other, 디렉토리 이름)
const deleteImages = async (fileList, myImages, confirm) => {
    try {
        let deleteList = (confirm !== 'delete' ? fileList.filter((item) => !myImages.has(item)) : fileList);

        if (deleteList.length !== 0) {
            let formData = new FormData();
            formData.append('deleteList', JSON.stringify(deleteList));

            const response = await fetch('/deleteImages', {
                method: 'POST', // 요청 방식 지정(GET, POST 등)
                body: formData, // 서버로 보낼 데이터
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            console.log(data); // 파싱된 데이터 출력
        }
    } catch (error) {
        console.error('Error:', error); // 에러 처리
    }
};

// content에서 S3에 저장된 형식의 이미지이름만 추출
function extractionValue(items) {
    let currentImgList = items.map((item) => {
        if (item['insert'] && typeof item['insert'] === 'object' && item['insert'].hasOwnProperty('image')) {
            return item['insert']['image'].split('images/')[1];
        }
        return null;
    }).filter(Boolean); // falsy값 제거

    return currentImgList;
};

const submitBtn = document.querySelector('#submit-btn');
const cancelBtn = document.querySelector('#cancel-btn');

cancelBtn.addEventListener('click', () => {
    deleteImages(sumImageList, new Set(), 'delete');
    window.location.href = `/post/${postData.id}`;
});

window.addEventListener('beforeunload', () => {
    deleteImages(sumImageList, new Set(), 'delete');
});

document.querySelector('#update-form').addEventListener('submit', function (event){
    event.preventDefault();
    
    const title = document.querySelector('#title').value;
    const context = document.querySelector('#context');
    const contentValue = editor.getContents().ops;
    const stringContent = JSON.stringify(contentValue);
    context.value = stringContent;

    let currentImgList = extractionValue(contentValue);

    if(currentImgList.length !== sumImageList.length){
        deleteImages(sumImageList, new Set(currentImgList), 'others');
    }

    if (title === '' || (( contentValue.length === 1  && contentValue[0]['insert'].trim() === ''))){
        return alert('입력창을 확인하세요');
    } 

    sumImageList = [];
    this.submit();
    
});


