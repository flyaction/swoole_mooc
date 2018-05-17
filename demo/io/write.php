<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/20
 * Time: 10:42
 */


$msg = date('Y-m-d H:i:s').PHP_EOL;


$result = swoole_async_writefile(__DIR__.'/1.log',$msg,function($name){
    echo 'writeOk'.PHP_EOL;
},FILE_APPEND);



var_dump($result);



echo 'start'.PHP_EOL;