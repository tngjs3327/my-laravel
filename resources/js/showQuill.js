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

let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

let eBtns = document.querySelectorAll('.edit-btn');
let dBtns = document.querySelectorAll('.delete-btn');
let sBtns = document.querySelectorAll('.save-btn');
let cBtns = document.querySelectorAll('.cancel-btn');

let beforeContent;

eBtns.forEach((element, index) => {
    element.addEventListener('click', (e) => editEvent(e, index));
});

dBtns.forEach((element, index) => {
    element.addEventListener('click', (e) => deleteEvent(e, index));
});

cBtns.forEach((element, index) => {
    element.addEventListener('click', (e) => cancelEvent(e, index));
});

sBtns.forEach((element, index) => {
    element.addEventListener('click', (e) => saveEvent(e, index));
});


function editEvent(event, index) {
    console.log(event.target, index);
    let commentId = event.target.dataset.id;
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
    let commentId = event.target.dataset.id;
    const commentContent = document.querySelector(`#comment-content${commentId}`);
    const newContent = commentContent.innerHTML.trim();

    if (beforeContent !== newContent) {
        try {
            updateComment(commentId, newContent);
            // 성공적으로 업데이트된 경우에 대한 추가 로직
        } catch (error) {
            console.error('Failed to update comment:', error);
            // 실패한 경우에 대한 추가 로직
        }
    }

    // UI 초기화
    commentContent.setAttribute('contenteditable', false);
    eBtns[index].classList.replace('hidden', 'block');
    dBtns[index].classList.replace('hidden', 'block');
    sBtns[index].classList.replace('block', 'hidden');
    cBtns[index].classList.replace('block', 'hidden');
}

function cancelEvent(event, index) {
    let commentId = event.target.dataset.id;
    const commentContent = document.querySelector(`#comment-content${commentId}`);
    commentContent.innerHTML = beforeContent;

    // UI 초기화
    commentContent.setAttribute('contenteditable', false);
    eBtns[index].classList.replace('hidden', 'block');
    dBtns[index].classList.replace('hidden', 'block');
    sBtns[index].classList.replace('block', 'hidden');
    cBtns[index].classList.replace('block', 'hidden');
}

async function updateComment(commentId, newContent) {
    const response = await fetch(`/comment/${commentId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            content: newContent,
        }),
    });

    if (response.ok) {
        const data = await response.json();
        console.log(data.message); // 성공 메시지
    } else {
        console.error('Failed to update comment:', response.status);
    }
}

function deleteEvent(event, index) {
    let commentId = event.target.dataset.id;
    try {
        deleteComment(commentId, index);
    } catch (error) {
        console.error('Failed to delete comment:', error);
    }
}

async function deleteComment(commentId, index) {
    const response = await fetch(`/comment/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
    });

    if (response.ok) {
        try {
            const data = await response.json();
            console.log(data.message); // 성공 메시지

            location.reload(true); // 새로고침
            // 댓글 삭제 후 UI에서도 삭제
            // const commentElement = document.querySelector(`#contextComments > .flex-row[data-id="${commentId}"]`);
            // if (commentElement) {
            //     commentElement.remove();
            // } else {
            //     console.error('Comment element not found in the UI');
            // }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
    } else {
        console.error(`Failed to delete comment: ${response.status}`);
    }
}

