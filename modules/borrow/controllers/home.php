<?php

/**
 * @filesource modules/borrow/controllers/home.php
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 *
 * @see https://www.kotchasan.com/
 */

namespace Borrow\Home;

use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=borrow-home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * ฟังก์ชั่นสร้าง card
     *
     * @param Request         $request
     * @param \Kotchasan\Html $card
     * @param array           $login
     */
    public static function addCard(Request $request, $card, $login)
    {
        if ($login) {
            $items = \Borrow\Home\Model::get($login);
            if (isset($items->allpending)) {
                \Index\Home\Controller::renderCard($card, 'icon-profile',  $login['name'], number_format($items->unactive), 'สมาชิกที่รอการยืนยันสิทธิ์เข้าใช้ระบบ', 'index.php?module=member');
            }
            if ($login['status'] == 2) {
  \Index\Home\Controller::renderCard($card, 'icon-exchange ', '{LNG_Can be approve}', number_format($items->allpending), ' {LNG_Waiting list}', 'index.php?module=borrow-report&amp;status=0');

            \Index\Home\Controller::renderCard(
                $card, 
                'icon-valid', 
                'สามารถอนุมัติส่งมอบ', 
                number_format($items->allconfirmed),  // ใช้จำนวนที่ส่งมอบ
                ' '.Language::get('อนุมัติส่งมอบ', null, 0),  // ป้ายสถานะส่งมอบ
                'index.php?module=borrow-report&status=2' // ลิงก์ที่ตรงกับสถานะส่งมอบ
            );
            \Index\Home\Controller::renderCard($card, 'icon-warning text-danger', $login['name'], number_format($items->allreturned), ' {LNG_Un-Returned items}', 'index.php?module=borrow-report&status=2&due=1');
            \Index\Home\Controller::renderCard($card,'icon-valid', $login['name'],number_format($items->alldelivered),' ' . Language::get('รายการส่งมอบแล้ว', null, 4),'index.php?module=borrow-report&status=5');


            }
            if ($login['status'] == 0 || $login['status'] == 3) {
                \Index\Home\Controller::renderCard($card, 'icon-exchange', $login['name'], number_format($items->pending), ' '.Language::get('BORROW_STATUS', null, 0), 'index.php?module=borrow-setup&amp;status=0');
                \Index\Home\Controller::renderCard($card, 'icon-valid', $login['name'], number_format($items->confirmed), ' '.Language::get('BORROW_STATUS', null, 2), 'index.php?module=borrow-setup&amp;status=2');
                \Index\Home\Controller::renderCard($card, 'icon-warning', $login['name'], number_format($items->returned), ' {LNG_Un-Returned items}', 'index.php?module=borrow-setup&amp;status=2&amp;due=1');
                \Index\Home\Controller::renderCard(
                    $card, 
                    'icon-valid', 
                    $login['name'], 
                    number_format($items->delivered),  // เปลี่ยนจาก pending เป็น delivered
                    ' '.Language::get('ส่งมอบ', null, 0),  // ป้ายสถานะส่งมอบ
                    'index.php?module=borrow-setup&amp;status=5' // ลิงก์ที่ตรงกับสถานะส่งมอบ
                );
    
            }
        }
    }
}