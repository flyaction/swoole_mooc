<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/23
 * Time: 14:48
 */


// pstree -p 主进程号
// ps aft |grep http_server



$process = new swoole_process(function(swoole_process $pro){
    //echo 111;
    $pro->exec("/alidata/server/php723/bin/php", [__DIR__.'/../server/http_server.php']);

},false);


$pid = $process->start();


echo $pid.PHP_EOL;

swoole_process::wait();