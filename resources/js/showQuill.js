import Quill from "quill";

let postDataElement = document.querySelector('#post-data');
let postData = JSON.parse(postDataElement.dataset.post);
let jsonString = postData.content;
let content = JSON.parse(jsonString);

let editor = new Quill('#quill-editor', {
    modules: {
        toolbar: false,
    },
    readOnly: true,
});

editor.setContents(content);

let eBtns = document.querySelectorAll('#edit-btn');
let dBtns = document.querySelectorAll('#delete-btn');
let sBtns = document.querySelectorAll('#save-btn');
let cBtns = document.querySelectorAll('#cancel-btn');

let beforeContent;
let commentId; // 댓글 ID를 설정해야 함

eBtns.forEach((element, index) => {
    element.addEventListener('click', editEvent(element, index));
});

dBtns.forEach((element, index) => {
    element.addEventListener('click', deleteEvent(element, index));
});

cBtns.forEach((element, index) => {
    element.addEventListener('click', cancelEvent(element, index));
});

sBtns.forEach((element, index) => {
    element.addEventListener('click', saveEvent(element, index));
});

function editEvent(event, index) {
    commentId = event.target.dataset.id;
    let commentContent = document.querySelector(`#comment-content${commentId}`);

    console.log("commentContent:", commentContent); // 디버깅용 로그

    commentContent.setAttribute('contenteditable', true);
    beforeContent = commentContent.innerHTML.trim();

    commentContent.focus();


    eBtns[index].classList.replace('block', 'hidden');
    dBtns[index].classList.replace('block', 'hidden');
    sBtns[index].classList.replace('hidden', 'block');
    cBtns[index].classList.replace('hidden', 'block');
}

async function saveEvent(event, index) {
    commentId = event.target.dataset.id;
    const commentContent = document.querySelector(`#comment-content${commentId}`);
    const newContent = commentContent.innerHTML.trim();

    if (beforeContent !== newContent) {
        try {
            await updateComment(commentId, newContent);
            // 성공적으로 업데이트된 경우에 대한 추가 로직
        } catch (error) {
            console.error('Failed to update comment:', error);
            // 실패한 경우에 대한 추가 로직
        }
    }

    // UI 초기화
    commentContent.setAttribute('contenteditable', false);
    eBtns[index].classList.replace('hidden', 'block');
    dBtns[index].classList.replace('hidden', 'hidden');
    sBtns[index].classList.replace('block', 'hidden');
    cBtns[index].classList.replace('block', 'hidden');
}

function cancelEvent(event, index) {
    commentId = event.target.dataset.id;
    const commentContent = document.querySelector(`#comment-content${commentId}`);
    commentContent.innerHTML = beforeContent;

    // UI 초기화
    commentContent.setAttribute('contenteditable', false);
    eBtns[index].classList.replace('hidden', 'block');
    dBtns[index].classList.replace('hidden', 'hidden');
    sBtns[index].classList.replace('block', 'hidden');
    cBtns[index].classList.replace('block', 'hidden');
}

async function deleteEvent(event, index) {
    commentId = event.target.dataset.id;
    try {
        await deleteComment(commentId);
        // 성공적으로 삭제된 경우에 대한 추가 로직
    } catch (error) {
        console.error('Failed to delete comment:', error);
        // 실패한 경우에 대한 추가 로직
    }
}

async function updateComment(commentId, newContent) {
    const response = await fetch(`/comments/${commentId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            content: newContent,
        }),
    });

    if (!response.ok) {
        throw new Error('Failed to update comment');
    }

    const data = await response.json();
    console.log(data.message); // 성공 메시지
}

async function deleteComment(commentId) {
    const response = await fetch(`/comments/${commentId}`, {
        method: 'DELETE',
    });

    if (!response.ok) {
        throw new Error('Failed to delete comment');
    }

    const data = await response.json();
    console.log(data.message); // 성공 메시지
}
