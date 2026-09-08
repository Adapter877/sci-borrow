<?php
/* settings/database.example.php
 *
 * คัดลอกไฟล์นี้เป็น settings/database.php แล้วกรอกค่าของเครื่องตัวเอง
 * ตัวติดตั้ง (install/) จะสร้างไฟล์จริงให้อัตโนมัติ และไฟล์จริงถูก .gitignore ไว้
 */
return array(
    'mysql' => array(
        'dbdriver' => 'mysql',
        'username' => '',
        'password' => '',
        'dbname' => '',
        'prefix' => 'app',
        'hostname' => 'localhost',
        'port' => '3306',
    ),
    'tables' => array(
        'category' => 'category',
        'language' => 'language',
        'number' => 'number',
        'borrow' => 'borrow',
        'borrow_items' => 'borrow_items',
        'inventory' => 'inventory',
        'inventory_meta' => 'inventory_meta',
        'inventory_items' => 'inventory_items',
        'user' => 'user',
    ),
);
