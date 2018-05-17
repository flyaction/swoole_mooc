<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;
use think\Exception;

class Index
{
    public function index()
    {


        //echo "<pre>";
        //print_r($_GET);
        return 'hello index';

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
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
