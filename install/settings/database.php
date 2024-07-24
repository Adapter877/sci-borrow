<?php
/* settings/database.php */

return array(
    'mysql' => array(
        'dbdriver' => 'mysql',
        'username' => 'root',
        'password' => '',
        'dbname' => 'borrow',
        'prefix' => 'app'
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
        'user' => 'user'
    )
);
