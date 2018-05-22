<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;
use think\Exception;

class Index
{
    public function index()
    {
        return '';

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function test()
    {
        return 'hello,test';
    }

    public function sms(){

        try
        {
            $res = Sms::sendSms('15010115181','123');
            return $res;
        }
        catch (Exception $e)
        {

        }




    }
}
