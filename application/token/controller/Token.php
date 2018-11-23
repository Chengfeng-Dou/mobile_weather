<?php
/**
 * Created by PhpStorm.
 * User: douchengfeng
 * Date: 2018/11/20
 * Time: 22:26
 */

namespace app\token\controller;


use app\token\logic\WeatherGetter;

class Token
{
    private $weatherGetter;

    public function __construct()
    {
        $this->weatherGetter = new WeatherGetter();
    }

    public function responseToken()
    {
        $echostr = $_GET['echostr'];
        if ($this->checkSignature()) {
            echo $echostr;
            exit;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>";
            if (!empty($keyword)) {
                $msgType = "text";
                $contentStr = $this->weatherGetter->getWeather($keyword);
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            } else {
                echo '咋不说哈呢';
            }
        } else {
            echo '咋不说哈呢';
            exit;
        }
    }

    public function showWeather(){
        $city = $_GET['city'];
        return $this->weatherGetter->getWeather($city);
    }


    private function checkSignature()
    {
        $nonce = $_GET['nonce'];
        $token = 'tlosp';
        $timestamp = $_GET['timestamp'];
        $signature = $_GET['signature'];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        return $tmpStr == $signature;
    }
}