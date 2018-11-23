<?php
/**
 * Created by PhpStorm.
 * User: douchengfeng
 * Date: 2018/11/21
 * Time: 10:16
 */

namespace app\authentication\controller;


use app\authentication\logic\AuthenticationLogic;
use Exception;
use think\Controller;

class Register extends Controller
{

    public function showPage()
    {
        $this->assign('invalidUsr', '');
        return view('register_page');
    }

    public function registerUsr()
    {
        $name = $_POST['name'];
        $password = $_POST['password'];
        try {
            AuthenticationLogic::registerUsr($name, $password);
            return view('register_success_page');
        } catch (Exception $exception) {
            $this->assign('invalidUsr', '该账户已经存在或者密码格式不正确');
            return view('register_page');
        }
    }
}