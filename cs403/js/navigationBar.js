let userData = null;
// ดึง userData จาก sessionStorage หรือ localStorage
if (sessionStorage.getItem('userData')) {
    userData = JSON.parse(sessionStorage.getItem('userData'));
} else if (localStorage.getItem("userDataStorage")) {
    userData = JSON.parse(localStorage.getItem("userDataStorage"));
}

let generateNavigationBar = document.querySelector('nav');

function checkToken() {
    if (userData && userData.length > 0) {
        return userData; // return userData when login success
    } else {
        return false;
    }
}

function generateNavigation() {
    console.log('generateNavigation called'); // ตรวจสอบว่าฟังก์ชันถูกเรียกใช้หรือไม่
    // ตรวจสอบว่า generateNavigationBar มีค่าหรือไม่
    if (generateNavigationBar) {
        fetch('./navigationBar.html') // ดึงเนื้อหา navigation bar จากไฟล์ navigationBar.html
            .then(response => response.text())
            .then(html => {
                generateNavigationBar.innerHTML = html;
                manageShowBtnNavigationBar(); // จัดการปุ่มหลังจากโหลด HTML แล้ว
                // setupCartIconListener(); // *** ลบการเรียกฟังก์ชันนี้ออก ***
                setupTypeLinkListener(); // *** แยก Listener ของ TypeLink ออกมา ***
            })
            .catch(error => console.error('Error fetching navigationBar.html:', error)); // เพิ่ม error handling
    }
}

// *** แยก Listener ของ TypeLink ออกมาเป็นฟังก์ชัน ***
function setupTypeLinkListener() {
    const typeLink = document.querySelector('.typeLink'); // หา .typeLink หลังจากโหลด HTML แล้ว
    if (typeLink) {
        typeLink.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default link behavior

            // Check if we are on index.html
            if (window.location.pathname.endsWith('index.html') || window.location.pathname === '/' || window.location.pathname.endsWith('/cs403/')) { // ปรับเงื่อนไขให้ครอบคลุม root path
                // We are on index.html, scroll directly
                const targetElement = document.getElementById('garbageType');
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                } else {
                    console.warn("Element with id 'garbageType' not found on index.html");
                }
            } else {
                // We are on a different page, redirect first then scroll
                localStorage.setItem('scrollToGarbageType', 'true');
                window.location.href = 'index.html';
            }
        });
    } else {
        console.warn("Element with class 'typeLink' not found in navigationBar.html");
    }

    // Add event listener for scrolling after redirecting to index.html
    // ควรจะทำงานเมื่อหน้า index.html โหลดเสร็จ
    if (window.location.pathname.endsWith('index.html') || window.location.pathname === '/' || window.location.pathname.endsWith('/cs403/')) {
        // ใช้ DOMContentLoaded จะเร็วกว่า load ถ้าไม่ต้องรอรูปภาพ
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('scrollToGarbageType')) {
                const targetElement = document.getElementById('garbageType');
                if (targetElement) {
                    // อาจจะต้องรอเล็กน้อยเพื่อให้ layout เสร็จสมบูรณ์ก่อน scroll
                    setTimeout(() => {
                         targetElement.scrollIntoView({ behavior: 'smooth' });
                    }, 100); // รอ 100ms
                } else {
                     console.warn("Element with id 'garbageType' not found on index.html after redirect.");
                }
                localStorage.removeItem('scrollToGarbageType');
            }
        });
    }
}


function manageShowBtnNavigationBar() {
    let haveToken = checkToken();
    // --- หา Element ใหม่ทุกครั้งที่ดีที่สุด เพราะ DOM อาจจะถูกเขียนทับ ---
    let getSignupAndLoginBtnBox = document.querySelector(".signup_And_login_BtnBx");
    let getAuthenticationBtnBox = document.querySelector(".authentication_uer_box");
    let getUserGreeting = document.querySelector(".user_Greeting");
    let userGreetingText = document.querySelector(".user-greeting-text");
    let userAccBx = document.querySelector(".userAcc_Bx");
    let logoutBtn = document.querySelector(".logoutBtn"); // หาปุ่ม Logout

    // จัดการปุ่ม Login/Signup และกล่องข้อมูล User
    if (getAuthenticationBtnBox) {
        getAuthenticationBtnBox.style.display = haveToken ? 'flex' : 'none'; // ใช้ display ดีกว่า class ในบางกรณี
    }
    if (getSignupAndLoginBtnBox) {
        getSignupAndLoginBtnBox.style.display = haveToken ? 'none' : 'flex';
    }
    if (getUserGreeting) {
        getUserGreeting.style.display = haveToken ? 'block' : 'none';
    }

    // แสดง welcome message และ user icon
    if (haveToken) {
        // แสดง welcome message
        if (userGreetingText) {
            let displayName = haveToken[0].firstname || 'ผู้ใช้'; // ใส่ default เผื่อไม่มี firstname
            if (haveToken[0].user_type === "organization") {
                displayName += " (องค์กร)";
            }
            userGreetingText.textContent = `สวัสดี, ${displayName}`;
        }
        // แสดง user icon
        if (userAccBx) {
            // ตรวจสอบว่ายังไม่มี icon อยู่ข้างในก่อนเพิ่ม
            if (!userAccBx.querySelector('a')) {
                 userAccBx.innerHTML = `<a href="./account.html" aria-label="Account"><i class='bx bxs-user'></i></a>`;
            }
        }
        // --- เพิ่ม Event Listener ให้ปุ่ม Logout ---
        if (logoutBtn && !logoutBtn.hasAttribute('data-listener-added')) { // เช็คว่ายังไม่ได้เพิ่ม listener
            logoutBtn.addEventListener('click', logout);
            logoutBtn.setAttribute('data-listener-added', 'true'); // ทำเครื่องหมายว่าเพิ่ม listener แล้ว
        }

    } else {
        // clear welcome message
        if (userGreetingText) {
            userGreetingText.textContent = "";
        }
        // clear user icon
        if (userAccBx) {
            userAccBx.innerHTML = ``;
        }
    }
}

function logout() {
    userData = null; // ลบข้อมูล userData ในตัวแปร
    sessionStorage.removeItem('userData'); // ลบ userData ออกจาก sessionStorage
    localStorage.removeItem("userDataStorage"); // ลบ localStorage ด้วย

    // ไม่ต้องเรียก generateNavigation() ซ้ำซ้อน เพราะ manageShowBtnNavigationBar() จะจัดการ UI เอง
    // และการ redirect จะโหลดหน้าใหม่ทั้งหมดอยู่แล้ว

    Swal.fire({
        icon: 'success',
        title: 'ออกจากระบบสำเร็จ',
        text: `คุณได้ออกจากระบบแล้ว`,
        timer: 1500, // แสดงแป๊บเดียว
        showConfirmButton: false
    }).then(() => {
        // Redirect ไปหน้า index หลังจาก SweetAlert ปิด
        window.location.href = 'index.html';
    });
}

//=============== สิ้นสุดส่วนที่เพิ่มเข้ามา ===============

// ฟังก์ชัน escape HTML (ควรมีอยู่แล้ว)
function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return unsafe;
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

// =============== สิ้นสุดส่วนที่เพิ่มเข้ามา ===============


// --- Event Listener หลัก: ใช้ DOMContentLoaded จะดีกว่า load ถ้าไม่รอรูปภาพ ---
// แต่เพื่อให้สอดคล้องกับโค้ดเดิมที่อาจมีส่วนอื่นรอ 'load' อยู่ จะยังใช้ 'load'
// แต่จะเรียก generateNavigation() ก่อน แล้วฟังก์ชันนั้นจะจัดการส่วนที่เหลือ
window.addEventListener('load', () => {
    generateNavigation(); // เริ่มต้นด้วยการโหลด nav bar ก่อน
    // ไม่ต้องเรียก manageShowBtnNavigationBar หรือ setupCartIconListener ที่นี่แล้ว
    // เพราะ generateNavigation จะเรียกมันหลังจาก fetch เสร็จ

    // ฟังก์ชันอื่นๆ ที่อาจจะต้องรอ DOM หรือข้อมูลอื่นๆ (ถ้ามี)
    // showQtyMenuCart(); // หากมีฟังก์ชันเหล่านี้
    // showQtyMenuWishlist();
    // stickBar();
    // showCartItem();
    // if (sessionStorage.getItem('userData')) {
    //     showWishlistItem();
    // }
});

// --- เพิ่ม CSS เล็กน้อยสำหรับจัดข้อความชิดซ้าย ---
// คุณสามารถใส่ CSS นี้ในไฟล์ CSS หลักของคุณ หรือใส่ใน <style> tag ใน HTML ก็ได้
/*
.swal2-html-container.swal2-html-container-left {
    text-align: left !important;
}
*/
