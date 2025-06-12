let getAccountDisplay = document.querySelector(".account .content");

window.addEventListener("load", displayAccountData());

// Authentication Token
function IfUserAlreadyLogout() {
  // Check if user logout We will not show this page to User. (delete token)
  let getToken = JSON.parse(localStorage.getItem("token")) || null;
  if (getToken == null) {
    window.location.href = "/index.html";
  }
}
IfUserAlreadyLogout();

function logoutHandle() {
  let checkToken = localStorage.getItem("token") || null;
  if (checkToken != null) {
    Swal.fire({
      title: "Do you want to log out now?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ok",
    }).then((result) => {
      if (result.isConfirmed) {
        localStorage.removeItem("token");
        location.reload();
      }
    });
  } else {
    Swal.fire({
      position: "center",
      icon: "warning",
      title: "Something went wrong. Please contact us.",
      showConfirmButton: false,
      timer: 1500,
    });
  }
}

function getDataFromToken() {
  let getToken = JSON.parse(localStorage.getItem("token")) || null;
  let getAllUser = JSON.parse(localStorage.getItem("userDataStorage")) || null;
  if (getToken != null && getAllUser != null) {
    let getUserDataFromToken = getAllUser.filter(
      (tokenID) => tokenID.id == getToken
    );
    return getUserDataFromToken[0];
  }
}

function displayAccountData() {
  let getData = getDataFromToken() || null;
  getAccountDisplay.innerHTML = "";
  if (getData != null) {
    let createUserContent = `
        <div class="image"><img src="/image/user1.png" alt=""></div>
            <ul class="account_data">
              <li><p>ไอดี :</p><p>${getData.id}</p></li>
              <li><p>ชื่อจริง :</p><p>${getData.firstname}</p></li>
              <li><p>นามสกุล :</p><p>${getData.lastname}</p></li>
              <li><p>อีเมล :</p><p>${getData.email}</p></li>
              <li><p>เบอร์โทรศัพท์ :</p><p>${getData.phone}</p></li>
            </ul>
            <button class="logoutBtn" onclick="logoutHandle()">Logout</button>
        `;
    getAccountDisplay.innerHTML = createUserContent;
  } else {
    let createUserContent = `
        <div class="image"><img src="/image/user1.png" alt=""></div>
            <ul class="account_data">
              <li><p>ไอดี :</p><p>---</p></li>
              <li><p>ชื่อจริง :</p><p>---</p></li>
              <li><p>นามสกุล :</p><p>---</p></li>
              <li><p>อีเมล :</p><p>---</p></li>
              <li><p>เบอร์โทรศัพท์ :</p><p>---</p></li>
            </ul>
            <button class="logoutBtn"> --- </button>
        `;
    getAccountDisplay.innerHTML = createUserContent;
  }
}

function deleteAccount() {
  Swal.fire({
    title: "คุณต้องการลบบัญชีนี้หรือไม่?",
    text: "การลบบัญชีนี้จะทำให้ข้อมูลทั้งหมดของคุณถูกลบออกจากระบบ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "ลบบัญชี",
    cancelButtonText: "ยกเลิก",
  }).then((result) => {
    if (result.isConfirmed) {
      // ลบบัญชี
      let getToken = JSON.parse(localStorage.getItem("token")) || null;
      let getAllUser = JSON.parse(localStorage.getItem("userDataStorage")) || [];

      // หาตำแหน่งของ user ที่ต้องการลบ
      let userIndex = getAllUser.findIndex((user) => user.id === getToken);

      if (userIndex !== -1) {
        // ลบ user ออกจาก array
        getAllUser.splice(userIndex, 1);

        // บันทึก userDataStorage ที่อัปเดตแล้วกลับไปที่ Local Storage
        localStorage.setItem("userDataStorage", JSON.stringify(getAllUser));
        localStorage.removeItem("token");
        localStorage.removeItem("userType");
        // แสดงข้อความสำเร็จและเปลี่ยนหน้า
        Swal.fire({
          title: "ลบบัญชีสำเร็จ!",
          text: "บัญชีของคุณถูกลบออกจากระบบเรียบร้อยแล้ว",
          icon: "success",
          confirmButtonText: "ตกลง",
        }).then(() => {
          window.location.href = "index.html";
        });
      }
    }
  });
}
