<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/3
 * Time: 15:40
 */

$table = new swoole_table(1024);

$table->column('id',$table::TYPE_INT);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,2);
$table->create();

$table->set('singwa_imooc', ['id' => 1, 'name'=> 'singwa', 'age' => 30]);
// 另外一种方案
$table['singwa_imooc_2'] = [
    'id' => 2,
    'name' => 'singwa2',
    'age' => 31,
];

$table->decr('singwa_imooc_2', 'age', 2);


print_r($table->get('singwa_imooc'));
print_r($table['singwa_imooc_2']);




