<?php

/**
 * Created by PhpStorm.
 * User: douchengfeng
 * Date: 2018/11/22
 * Time: 18:49
 */

namespace app\token\logic;

use DOMDocument;
use think\Db;
use think\Exception;

class WeatherGetter
{
    public function getWeather($cityName)
    {
        try {
            $result_set = DB::table('city')
                ->where('area', $cityName)
                ->find();
            $cityCode = $result_set['code'];
            $url = 'http://wthrcdn.etouch.cn/WeatherApi?citykey=' . $cityCode;
            $content = $this->openUrl($url);
            return $this->parseXML($content);
        } catch (Exception $e) {
            echo $e->getMessage();
            return '暂无该城市的天气信息';
        }


    }


    private function parseXML($content)
    {
        $doc = new DOMDocument();
        $success = $doc->loadXML($content);
        if(!$success){
            throw new Exception('无效的xml文件');
        }
        $city = $this->getSingleNodeValue($doc, 'city');
        $updateTime = $this->getSingleNodeValue($doc, "updatetime");
        $wendu = $this->getSingleNodeValue($doc, "wendu");
        $shidu = $this->getSingleNodeValue($doc, "shidu");
        $fengli = $this->getSingleNodeValue($doc, 'fengli');
        $fengxiang = $this->getSingleNodeValue($doc, 'fengxiang');

        $result = '城市：' . $city . "\n "
            . '更新时间：' . $updateTime . "\n "
            . '温度：' . $wendu . "\n "
            . '湿度：' . $shidu . "\n "
            . '风力：' . $fengli . "\n "
            . '风向：' . $fengxiang;


        return $result;
    }

    private function getSingleNodeValue($dom, $tagName)
    {
        return $dom->getElementsByTagName($tagName)->item(0)->nodeValue;
    }

    private function openUrl($url)
    {
        $content = file_get_contents($url);
        return gzdecode($content);
    }
}