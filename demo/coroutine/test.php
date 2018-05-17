<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/3/19
 * Time: 上午1:24
 */

$http = new swoole_http_server('0.0.0.0', 8001);

$http->on('request', function($request, $response) {


    // 获取redis 里面 的key的内容， 然后输出浏览器
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 7379);
    $redis->auth('1413');
    $value = $redis->get($request->get['a']);
    //$value = $redis->get('name');
//    $response->header("Content-Type", "text/plain");
//    $response->end($value);


    $swoole_mysql = new Swoole\Coroutine\MySQL();
    $swoole_mysql->connect([
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => 'yiyi1314',
        'database' => 'swoole',
    ]);
    $res = $swoole_mysql->query('select * from test');

    $response->header("Content-Type", "text/plain");
    $response->end(json_encode($res,JSON_UNESCAPED_UNICODE));
});

$http->start();



