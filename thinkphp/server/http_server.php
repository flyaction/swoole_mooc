<?php

	$http = new swoole_http_server('0.0.0.0',8811);

	$http->set(
		[
        	'enable_static_handler' => true,
        	'document_root' => "/alidata/www/swoole/mooc/thinkphp/public/static",
    	]
	);

    $http->on('WorkerStart',function(swoole_server $serv, $worker_id){
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');

        // 加载框架引导文件
        //require __DIR__ . '/../thinkphp/start.php';

        // 加载基础文件
        require __DIR__ . '/../thinkphp/base.php';

    });

	$http->on('request',function($request,$response)use($http){

        $_SERVER = [];
        if(isset($request->server)){
            foreach($request->server as $k=>$v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->header)){
            foreach($request->header as $k=>$v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if(isset($request->get)){
            foreach($request->get as $k=>$v){
                $_GET[$k] = $v;
            }
        }

        $_POST = [];
        if(isset($request->post)){
            foreach($request->post as $k=>$v){
                $_POST[$k] = $v;
            }
        }

        ob_start();

        try{
            // 执行应用并响应
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        }catch (\Exception $e){

        }

        $res = ob_get_contents();
        ob_end_clean();

		$response->end($res);

	});

	$http->start();

// topthink/think-swoole
// composer require topthink/think-swoole
//https://github.com/top-think/think-swoole