<?php
/* config.php */
$ip = getenv('LATEST_TAG');
return array (
  'version' => $ip,
  'web_title' => 'ระบบยืม-คืนวัสดุครุภัณฑ์ประจำห้องปฏิบัติการหลักสูตร ฟิสิกส์ เคมี ชีววิทยา Sak_tec Thursday 17 October 2024 08:48',
  'web_description' => 'ระบบยืม-คืนวัสดุครุภัณฑ์ประจำห้องปฏิบัติการหลักสูตร ฟิสิกส์ เคมี ชีววิทยา Sak_tec Thursday 17 October 2024 08:48',
  'timezone' => 'Asia/Bangkok',
  'member_status' => 
  array (
    0 => 'สมาชิก',
    1 => 'ผู้ดูแลระบบ',
  ),
  'color_status' => 
  array (
    0 => '#259B24',
    1 => '#FF0000',
  ),
  'default_icon' => 'icon-exchange',
  'inventory_w' => 600,
  'borrow_no' => '%04d',
  'borrow_prefix' => 'B%Y%M-',
  'password_key' => '66a070413282b',
  'reversion' => 1721897060,
  'stored_img_type' => '.jpg',
  'login_message' => '',
  'login_message_style' => 'hidden',
  'login_header_color' => '#FFFFFF',
  'login_footer_color' => '#FFFFFF',
  'login_color' => '#FFFFFF',
  'login_bg_color' => '#5D97F1',
  'skin' => 'skin/default',
  'show_title_logo' => 1,
  'new_line_title' => 1,
  'header_bg_color' => '#5D97F1',
  'warpper_bg_color' => '#F9F9F9',
  'content_bg' => '#FFFFFF',
  'header_color' => '#FFFFFF',
  'footer_color' => '#999999',
  'logo_color' => '#FFFFFF',
  'theme_width' => 'fullwidth',
  'user_forgot' => 0,
  'user_register' => 1,
  'welcome_email' => 0,
  'demo_mode' => 0,
  'activate_user' => 0,
  'new_members_active' => 0,
  'login_fields' => 
  array (
    0 => 'username',
    1 => 'id_card',
  ),
  'default_department' => '',
  'facebook_appId' => '',
  'google_client_id' => '',
);