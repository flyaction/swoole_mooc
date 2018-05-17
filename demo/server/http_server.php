<?php

	$http = new swoole_http_server('0.0.0.0',8811);

	$http->set(
		[
        	'enable_static_handler' => true,
        	'document_root' => "/alidata/www/swoole/imooc/data",
    	]
	);

	$http->on('request',function($request,$response){


        $content = [
            'date:' => date("Ymd H:i:s"),
            'get:' => $request->get,
            'post:' => $request->post,
            'header:' => $request->header,
        ];

        swoole_async_writefile(__DIR__."/access.log", json_encode($content).PHP_EOL, function($filename){
        }, FILE_APPEND);

		$response->cookie("action", "xsssss", time() + 1800);

		$get = isset($request->get)?$request->get:'';
		if($get){
			$gets = json_encode($request->get);	
		}else{
			$gets = '';
		}
		$response->end("ddd".$gets);
	});

	$http->start();
