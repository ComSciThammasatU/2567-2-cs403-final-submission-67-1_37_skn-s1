document.addEventListener("DOMContentLoaded", function() {
    loadActivities();
    const activityForm = document.getElementById('activityForm');
    activityForm.addEventListener('submit', function (event) {
        event.preventDefault();
        addActivity();
    });
});

function checkToken(){
    let token = sessionStorage.getItem("userData");
    if(token){
        let user = JSON.parse(token)[0];
        return user; // คืนค่าเป็น object user
    }else{
        return false;
    }
}

function addActivity() {
    const title = document.getElementById("activityTitle").value;
    const detail = document.getElementById("activityDetail").value;
    const imageFile = document.getElementById("activityImage").files[0];
    const attachedFile = document.getElementById("activityFile").files[0];
    const user = checkToken();

    console.log("title:", title);
    console.log("detail:", detail);
    console.log("imageFile:", imageFile);
    console.log("attachedFile:", attachedFile);
    console.log("user:", user);

    if (title && detail && imageFile && user) {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('detail', detail);
        formData.append('image', imageFile);
        formData.append('file', attachedFile);
        formData.append('userId', user.id);

        fetch('./create_activity.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("data:", data);
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'โพสต์กิจกรรมสำเร็จ',
                    text: 'กิจกรรมของคุณถูกโพสต์แล้ว',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("activityTitle").value = "";
                        document.getElementById("activityDetail").value = "";
                        document.getElementById("activityImage").value = "";
                        document.getElementById("activityFile").value = "";
                        loadActivities();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถโพสต์กิจกรรมได้ในขณะนี้',
            });
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: 'กรุณากรอกข้อมูลให้ครบถ้วน หรือ เข้าสู่ระบบก่อนโพส',
        });
    }
}

function loadActivities() {
    fetch('./get_activities.php')
        .then(response => response.json())
        .then(activities => {
            console.log("activities:", activities);
            const activitiesContainer = document.getElementById("activities");
            activitiesContainer.innerHTML = "";

            if (activities.length > 0) {
                activities.forEach(activity => {
                    const activityElement = document.createElement("div");
                    activityElement.className = "activity-box";
                    activityElement.innerHTML = `
                        <h3>${activity.title}</h3>
                        <img src="${activity.image_path}" alt="${activity.title}">
                        <div class="activity-detail">
                            <p>รายละเอียด: ${activity.detail}</p>
                            <p>ผู้โพสต์: ${activity.author}</p>
                            <p>วันที่: ${activity.timestamp}</p>
                        </div>
                    `;
                    activitiesContainer.appendChild(activityElement);
                });
            } else {
                activitiesContainer.innerHTML = "<p>ยังไม่มีกิจกรรม</p>";
            }
        })
        .catch(error => console.error('Error:', error));
}
