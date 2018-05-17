<?php
namespace app\index\controller;

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
}
