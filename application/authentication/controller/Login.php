<?php
/**
 * Created by PhpStorm.
 * User: douchengfeng
 * Date: 2018/11/14
 * Time: 10:42
 */

namespace app\authentication\controller;


use app\authentication\logic\AuthenticationLogic;
use think\Controller;

/** 登陆的控制器
 * Class Login
 * @package app\authentication\controller
 */
class Login extends Controller
{


    /** 打开登陆页面
     * @return \think\response\View
     */
    public function login()
    {
        $this->assign('errorMsg', '');
        return view('login_page');
    }

    /** 用于验证登陆是否成功
     * @return string
     */
    public function check()
    {
        $name = $_POST['name'];
        $password = $_POST['password'];

        if (AuthenticationLogic::isValidUsr($name, $password)) {
            return view('login_success_page');
        } else {
            $this->assign('errorMsg', '错误的用户名或密码');
            return view('login_page');
        }
    }
}