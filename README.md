[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/w8H8oomW)
**<ins>Note</ins>: Students must update this `README.md` file to be an installation manual or a README file for their own CS403 projects.**

รหัสโครงงาน: 67-1_37_skn-s1

ชื่อโครงงาน (ไทย): ช่องทางใหม่ในการจัดการขยะให้เป็นมิตรต่อสิ่งแวดล้อม

Project Title (Eng): TRASH HAS VALUE

อาจารย์ที่ปรึกษาโครงงาน: อ. สิริกันยา นิลพานิช

ผู้จัดทำโครงงาน:
1. นายพีรวิชญ์ บุตรสา  6209650586  peerawit.boo@dome.tu.ac.th

   
Manual / Instructions for your projects starts here !
# Table of Contents
   Directory Tree
   ขั้นตอนการโหลดไฟล์มี 2 วิธี
   ชุดโปรแกรมที่จำเป็นต้องติดตั้ง
   วิธีการติดตั้งระบบ
   วิธีการใช้งานเว็บแอปพลิเคชัน
# Directory Tree
   ├── HTML Files/             # ไฟล์ HTML สำหรับหน้าเว็บต่างๆ
│   ├── about.html          # หน้าเกี่ยวกับเรา
│   ├── address.html        # หน้าจัดการที่อยู่
│   ├── Allbuy.html         # หน้าแสดงรายการซื้อทั้งหมด 
│   ├── buypaper1.html      # หน้าสำหรับซื้อกระดาษ 
│   ├── buyplastic.html     # หน้าสำหรับซื้อพลาสติก 
│   ├── contact.html        # หน้าติดต่อเรา
│   ├── index.html          # หน้าหลักของผู้ขาย
│   ├── index1.html         # หน้าหลักของผู้ซื้อ
│   ├── navigationBar.html  # ส่วนแถบนำทาง 
│   ├── paper1.html         # หน้าแสดงขายกระดาษ
│   ├── pastic.html         # หน้าแสดงขายพลาสติก
│   ├── point.html          # หน้าแสดงคะแนนสะสม
│   ├── service.html        # หน้าเพิ่มกิจกกรม
│   ├── signup.html         # หน้าลงทะเบียน
│   ├── status_org.html     # หน้าแสดงสถานะสำหรับองค์กร
│   ├── status.html         # หน้าแสดงสถานะทั่วไป
│   ├── web1.html           # หน้าเว็บบอร์ด
│   ├── web2.html           # หน้าจัดการกลุ่มขยะ
│
├── PHP Files/              # ไฟล์ PHP สำหรับการประมวลผลฝั่งเซิร์ฟเวอร์และเชื่อมต่อฐานข้อมูล
│   ├── add_trash.php       # สคริปต์สำหรับเพิ่มข้อมูลขยะ
│   ├── config.php          # ไฟล์ตั้งค่า/การกำหนดค่าระบบ (เช่น การเชื่อมต่อฐานข้อมูล)
│   ├── create_activity.php # สคริปต์สำหรับสร้างกิจกรรมใหม่
│   ├── create_comment.php  # สคริปต์สำหรับสร้างความคิดเห็นใหม่
│   ├── create_group.php    # สคริปต์สำหรับสร้างกลุ่มใหม่
│   ├── create_post.php     # สคริปต์สำหรับสร้างโพสต์ใหม่
│   ├── delete_activity.php # สคริปต์สำหรับลบกิจกรรม
│   ├── delete_post.php     # สคริปต์สำหรับลบโพสต์
│   ├── delete_recycling.php# สคริปต์สำหรับลบข้อมูลการรีไซเคิล
│   ├── get_activity_titles.php # สคริปต์สำหรับดึงชื่อกิจกรรม
│   ├── get_address.php     # สคริปต์สำหรับดึงข้อมูลที่อยู่
│   ├── get_buy_requests.php# สคริปต์สำหรับดึงคำขอซื้อ
│   ├── get_buyers.php      # สคริปต์สำหรับดึงข้อมูลผู้ซื้อ
│   ├── get_comments.php    # สคริปต์สำหรับดึงความคิดเห็น
│   ├── get_groups.php      # สคริปต์สำหรับดึงข้อมูลกลุ่ม
│   ├── get_leaderboard.php # สคริปต์สำหรับดึงข้อมูลกระดานผู้นำ
│   ├── get_messages.php    # สคริปต์สำหรับดึงข้อความ
│   ├── get_org_recyclings.php # สคริปต์สำหรับดึงข้อมูลการรีไซเคิลขององค์กร
│   ├── get_posts.php       # สคริปต์สำหรับดึงโพสต์
│   ├── get_services.php    # สคริปต์สำหรับดึงข้อมูลบริการ
│   ├── get_trash.php       # สคริปต์สำหรับดึงข้อมูลขยะ
│   ├── get_user_recyclings.php # สคริปต์สำหรับดึงข้อมูลการรีไซเคิลของผู้ใช้
│   ├── getCurrentUserId.php# สคริปต์สำหรับดึง ID ผู้ใช้ปัจจุบัน
│   ├── getUserData.php     # สคริปต์สำหรับดึงข้อมูลผู้ใช้
│   ├── login.php           # สคริปต์สำหรับจัดการการเข้าสู่ระบบ
│   ├── register.php        # สคริปต์สำหรับจัดการการลงทะเบียน
│   ├── save_address.php    # สคริปต์สำหรับบันทึกที่อยู่
│   ├── save_buy_request.php# สคริปต์สำหรับบันทึกคำขอซื้อ
│   ├── save_recycling.php  # สคริปต์สำหรับบันทึกข้อมูลการรีไซเคิล
│   ├── send_message.php    # สคริปต์สำหรับส่งข้อความ
│   ├── update_gamification.php 
│   ├── update_recycling_status.php # สคริปต์สำหรับอัปเดตสถานะการรีไซเคิล
│   └── update_topic.php    # สคริปต์สำหรับอัปเดตหัวข้อ
   

# ขั้นตอนการโหลดไฟล์มี 2 วิธี
# ชุดโปรแกรมที่จำเป็นต้องติดตั้ง
# วิธีการติดตั้งระบบ
# วิธีการใช้งานเว็บแอปพลิเคชัน

