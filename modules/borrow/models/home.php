<?php
/**
 * @filesource modules/borrow/models/home.php
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 *
 * @see https://www.kotchasan.com/
 */

namespace Borrow\Home;

use Gcms\Login;
use Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับอ่านข้อมูลแสดงในหน้า Home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านรายการจองวันนี้.
     *
     * @return object
     */
    public static function get($login)
    {
        // รอตรวจสอบ
        $q0 = static::createQuery()
            ->select(Sql::COUNT())
            ->from('borrow W')
            ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
            ->where([
                ['W.borrower_id', $login['id']],
                ['S.status', 0]
            ]);
        // ครบกำหนดคืน
        $q1 = static::createQuery()
            ->select(Sql::COUNT())
            ->from('borrow W')
            ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
            ->where([
                ['W.borrower_id', $login['id']],
                ['S.status', 2],
                [Sql::DATEDIFF('W.return_date', date('Y-m-d')), '<=', 0]
            ]);
        // อนุมัติ/ใช้งานอยู่
        $q2 = static::createQuery()
            ->select(Sql::COUNT())
            ->from('borrow W')
            ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
            ->where([
                ['W.borrower_id', $login['id']],
                ['S.status', 2]
            ])
            ->andWhere([
                [Sql::DATEDIFF('W.return_date', date('Y-m-d')), '>', 0],
                Sql::ISNULL('W.return_date')
            ], 'OR');
        // ส่งมอบ (status = 5)
        $q3 = static::createQuery()
            ->select(Sql::COUNT())
            ->from('borrow W')
            ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
            ->where([
                ['W.borrower_id', $login['id']],
                ['S.status', 5]
            ]);
        
        if (Login::checkPermission($login, 'can_approve_borrow')) {
            // รายการรอตรวจสอบทั้งหมด
            $q4 = static::createQuery()
                ->select(Sql::COUNT())
                ->from('borrow W')
                ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
                ->where(['S.status', 0]);
            $q5 = static::createQuery()
                ->select(Sql::COUNT())
                ->from('borrow W')
                ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
                ->where(['S.status', 2]);
            $q6 = static::createQuery()
                ->select(Sql::COUNT())
                ->from('borrow W')
                ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
                ->where([
                    ['S.status', 2],  // สถานะต้องเป็น 2
                    [Sql::DATEDIFF('W.return_date', date('Y-m-d')), '<=', 0]  // ถ้า return_date ครบกำหนด
                ]);
            $q7 = static::createQuery()
                ->select(Sql::COUNT())
                ->from('user U')
                ->where(array('U.active', 0));
            $q8 = static::createQuery()
                ->select(Sql::COUNT())
                ->from('borrow W')
                ->join('borrow_items S', 'INNER', array('S.borrow_id', 'W.id'))
                ->where(array('S.status', 5));            
            $q9 = static::createQuery()
                ->select(Sql::COUNT())
                ->from('borrow W')
                ->join('borrow_items S', 'INNER', ['S.borrow_id', 'W.id'])
                ->where(['S.status', 3]);

            return static::createQuery()->cacheOn()->first([
                $q0, 'pending'],
                [$q1, 'returned'],
                [$q2, 'confirmed'],
                [$q3, 'delivered'],   // เพิ่มการดึงจำนวน "ส่งมอบ"
                [$q4, 'allpending']
            ,   [$q5, 'allconfirmed'],
                [$q6, 'allreturned'],
                [$q7, 'unactive'],
                [$q8, 'alldelivered'],
                [$q8, 'allreturned1'],
                


            );
        } else {
            return static::createQuery()->cacheOn()->first([
                $q0, 'pending'],
                [$q1, 'returned'],
                [$q2, 'confirmed'],
                [$q3, 'delivered']   // เพิ่มการดึงจำนวน "ส่งมอบ"
            );
        }
    }
}
