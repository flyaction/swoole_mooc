<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Util;
use app\common\lib\Redis;

class Send
{
    /*
     * 发送验证码
     */
    public function index()
    {
        //$phoneNum = request()->get('phone_num',0,'intval');
        $phoneNum = intval($_GET['phone_num']);
        if(!$phoneNum){
            return Util::show(config('code.error'),'error');
        }

        // 生成一个随机数
        $code = rand(1000, 9999);

        //使用task异步任务
        $taskData = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phoneNum,
                'code' => $code,
            ]
        ];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), 'ok');

        //不用异步任务之前的短信发送逻辑
//        try {
//           $response = Sms::sendSms($phoneNum, $code);
//        }catch (\Exception $e) {
//
//           return Util::show(config('code.error'), '阿里大于内部异常');
//        }
//        if($response->Code !== "OK") {//这里其实是 === OK,但是我没购买短信，所有暂时先这样
//            $redis = new \Swoole\Coroutine\Redis();
//            $redis->connect(config('redis.host'), config('redis.port'));
//            $redis->auth(config('redis.auth'));
//            $redis->set(Redis::smsKey($phoneNum), $code, config('redis.out_time'));
//            return Util::show(config('code.success'), 'success');
//        } else {
//            return Util::show(config('code.error'), '验证码发送失败');
//        }


    }

}
