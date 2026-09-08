<?php
/* settings/config.example.php
 *
 * คัดลอกไฟล์นี้เป็น settings/config.php แล้วปรับค่าตามการติดตั้ง
 * password_key ต้องสุ่มใหม่ต่อการติดตั้งหนึ่งครั้ง ห้ามใช้ค่าเดียวกับที่อื่น
 * และห้ามคอมมิตไฟล์จริงลง repo เด็ดขาด
 */
$ip = getenv('LATEST_TAG');
return array(
    'version' => $ip,
    'web_title' => 'SCI-Borrow',
    'web_description' => 'ระบบยืม-คืนวัสดุครุภัณฑ์ประจำห้องปฏิบัติการ',
    'timezone' => 'Asia/Bangkok',
    'member_status' => array(0 => 'สมาชิก', 1 => 'ผู้ดูแลระบบ'),
    'color_status' => array(0 => '#259B24', 1 => '#FF0000'),
    'default_icon' => 'icon-exchange',
    'inventory_w' => 600,
    'borrow_no' => '%04d',
    'borrow_prefix' => 'B%Y%M-',
    // สุ่มใหม่ทุกครั้ง เช่น  php -r "echo bin2hex(random_bytes(8));"
    'password_key' => '',
    'stored_img_type' => '.jpg',
    'skin' => 'skin/default',
    'facebook_appId' => '',
    'google_client_id' => '',
);
