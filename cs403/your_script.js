// สมมติว่าคุณมี form ที่มี id="commentForm" และมี div ที่มี id="commentsContainer" สำหรับแสดงคอมเมนต์
const commentForm = document.getElementById('commentForm');
const commentsContainer = document.getElementById('commentsContainer');

commentForm.addEventListener('submit', function(event) {
    event.preventDefault(); // ป้องกันการรีเฟรชหน้าเว็บ

    const formData = new FormData(commentForm);

    fetch('add_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // แสดงคอมเมนต์ใหม่
            const newComment = data.comment;
            const commentElement = document.createElement('div');
            commentElement.innerHTML = `
                <p><strong>${newComment.author}:</strong> ${newComment.content}</p>
                <p>Timestamp: ${newComment.timestamp}</p>
                <hr>
            `;
            commentsContainer.appendChild(commentElement);

            // ล้าง form
            commentForm.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('An error occurred.');
    });
});
