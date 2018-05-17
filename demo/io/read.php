<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/20
 * Time: 10:42
 */


$result = swoole_async_readfile(__DIR__.'/1.txt',function($name,$content){
    echo $name.PHP_EOL;
    echo $content;
});

//$result = Swoole\Async::readFile(__DIR__.'/1.txt',function($name,$content){
//    echo $name.PHP_EOL;
//    echo $content;
//});


var_dump($result);



echo 'start'.PHP_EOL;