<?php
/* database.php */
return array (
  'mysql' => 
  array (
    'dbdriver' => 'mysql',
    'username' => 'root',
    'password' => 'password',
    'dbname' => 'borrow',
    'prefix' => 'app',
    'hostname' => '192.168.88.211',
    'port' => '3306',
  ),
  'tables' => 
  array (
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