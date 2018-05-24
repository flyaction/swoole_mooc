<?php


class Ws {

    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;
    CONST CHART_PORT = 8812;

    public $ws = null;
    public function __construct() {

        //清空用户连接集合
//        if(\app\common\lib\redis\Predis::getInstance()->sMembers(config('redis.live_game_key'))){
//            \app\common\lib\redis\Predis::getInstance()->del(config('redis.live_game_key'));
//        }


        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        //监听端口
        $this->ws->listen(self::HOST, self::CHART_PORT,SWOOLE_SOCK_TCP);

        $this->ws->set(
            [
                'enable_static_handler' => true,
                'document_root' => "/alidata/www/swoole/imooc/thinkphp/public/static",
                'worker_num' => 4,
                'task_worker_num' => 4,
            ]
        );

        $this->ws->on("start", [$this, 'onStart']);

        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);

        $this->ws->on("workerstart", [$this, 'onWorkerStart']);
        $this->ws->on("request", [$this, 'onRequest']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);
        $this->ws->on("close", [$this, 'onClose']);

        $this->ws->start();
    }


    /**
     * @param $server
     */
    public function onStart($server) {
        swoole_set_process_name("live_master");
    }


    /*
     * 进程启动回调
     */
    public function onWorkerStart($serv, $worker_id) {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../../../application/');

        // 加载框架引导文件
        require __DIR__ . '/../../../thinkphp/start.php';

        // 加载基础文件
        //require __DIR__ . '/../thinkphp/base.php';

    }

    /*
     *
     */
    public function onRequest($request,$response) {
        //过滤favicon.ico请求
        if($request->server['request_uri'] == '/favicon.ico') {
            $response->status(404);
            $response->end();
            return ;
        }
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

        $_FILES = [];
        if(isset($request->files)){
            foreach($request->files as $k=>$v){
                $_FILES[$k] = $v;
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
        //记录日志
        $this->writeLog();
        //----
        $_POST['http_server'] = $this->ws;

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
    }


    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     */
    public function onTask($serv, $taskId, $workerId, $data) {

        //分发task异步任务,让不同的任务走不同的逻辑
        $obj = new app\common\lib\task\Task();
        $method = $data['method'];
        $flag = $obj->$method($data['data'],$serv);
        return $flag;
    }

    /**
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request) {
        \app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'),$request->fd);
        var_dump($request->fd);
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame) {
        echo "ser-push-message:{$frame->data}\n";
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
    }

    /**
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd) {
        \app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'),$fd);
        echo "clientid:{$fd}\n";
    }


    /**
     * 记录日志
     */
    public function writeLog() {
        $datas = array_merge(['date' => date("Ymd H:i:s")],$_GET, $_POST, $_SERVER);

        $logs = "";
        foreach($datas as $key => $value) {
            $logs .= $key . ":" . $value . " ";
        }

        swoole_async_writefile(APP_PATH.'../runtime/log/'.date("Ym")."/".date("d")."_access.log", $logs.PHP_EOL, function($filename){
            // todo
        }, FILE_APPEND);

    }
}

new Ws();


// 20台机器    agent -> spark (计算) - 》 数据库   elasticsearch  hadoop

// sigterm sigusr1(重启worker进程) usr2