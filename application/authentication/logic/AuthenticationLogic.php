<?php
/**
 * Created by PhpStorm.
 * User: douchengfeng
 * Date: 2018/11/14
 * Time: 15:57
 */

namespace app\authentication\logic;


use think\Db;
use think\Exception;


/** 用于身份认证以及注册
 * Class AuthenticationLogic
 * @package app\authentication\logic
 */
class AuthenticationLogic
{
    /** 用于判断用户的账户或者密码是否正确
     * @param $usrName string 用户名
     * @param $password string 密码
     * @return bool 如果匹配，那么返回 true，否则返回 false
     */
    public static function isValidUsr($usrName, $password)
    {
        try {
            $result = Db::table('user')
                ->where('name', $usrName)
                ->find();

            return md5($password) === $result['password'];
        } catch (Exception $e) {
            return false;
        }
    }
    /** 用于注册用户
     * @param $usrName
     * @param $password
     * @return int|string
     */
    public static function registerUsr($usrName, $password)
    {
        $password = md5($password);
        Db('user')->insert([
            'name' => $usrName,
            'password' => $password
        ]);
        return null;
    }
}