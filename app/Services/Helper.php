<?php
/**
 * Created by PhpStorm.
 * User: 冯威
 * Date: 2018/5/17
 * Time: 14:38
 */

namespace App\Services;


use App\Models\Log;

class Helper
{
    /**
     * 获得客户端IP地址
     * @return string
     */
    public static function getIP()
    {
        if(!empty($_SERVER["HTTP_CLIENT_IP"]))
        {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if(!empty($_SERVER["REMOTE_ADDR"]))
        {
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        else
        {
            $cip = '';
        }
        preg_match("/[\d\.]{7,15}/", $cip, $cips);
        $cip = isset($cips[0]) ? $cips[0] : 'unknown';
        unset($cips);

        return $cip;
    }

    /**
     * 判断IP地址是否正确
     * @param $str
     * @return bool|false|int
     */
    function is_ip($str)
    {
        $ip = explode('.', $str);
        for ($i = 0; $i < count($ip); $i++) {
            if ($ip[$i] > 255) {
                return false;
            }
        }
        return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $str);
    }

    /**
     * 获得地区
     * @return bool|string
     */
    public static function getCity()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if(empty($res)){
            return false;
        }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){ return false; }
        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1){
            $json['ip'] = $ip;
            unset($json['ret']);
        }else{
            return false;
        }
        return $json['province'] . $json['city'];
    }

    /**
     * 上传图片
     * @param $img
     * @param $auth
     * @return array
     */
    public static  function uploadImg($img, $auth)
    {
        if ($img->isValid()) {                                          //判断上传文件是否有效
            $images = array(                                              //文件格式限制
                'jpg', 'png', 'jpeg', 'gif', 'bmg'
            );
            if (in_array($img->getClientOriginalExtension(), $images)) {         //判断文件后缀是否符合限制
                $size = 2 * 1024 * 1024;                                           //文件大小限制2M
                if ($img->getClientSize() < $size) {
                    $name = time() . rand(1000, 9999) . "." . $img->getClientOriginalExtension();      //文件重命名
                    $img->move($auth, $name);                 //保存文件，并获取url
                    $url = $auth . '/' . $name;
                    $data = [
                        'error' => 0,
                        'data' => $url
                    ];
                    return $data;
                } else {
                    $data = [
                        'error' => 1,
                        'data' => '图片超过大小限制'
                    ];
                    return $data;
                }

            } else {
                $data = [
                    'error' => 1,
                    'data' => '图片格式不正确'
                ];
                return $data;
            }
        } else {
            $data = [
                'error' => 1,
                'data' => '上传的不是有效的文件'
            ];
            return $data;
        }
    }

    /**
     * 记录日志
     * @param $title  内容
     * @param $type  1:安全日志  2:操作日志  3:登录日志
     */
    public static function plog($title, $type)
    {
        $admin_id = session('admin_id');
        $data = Log::create([
            'admin_id'     => $admin_id,
            'title'        => $title,
            'type'         => $type,
            'created_time' => time(),
        ]);
    }

    public static function getType($type)
    {
        switch ($type)
        {
            case 0:
                return "实卡充值";
            case 1:
                return "支付宝";
            case 1:
                return "微信";
            case 1:
                return "银行卡";
            case 1:
                return "其它";
        }
    }
}