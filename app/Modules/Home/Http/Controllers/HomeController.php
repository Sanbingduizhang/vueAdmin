<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class HomeController extends ApiBaseController
{
    const APP_ID = '11689133';
    const API_KEY = 'y3XQLsq5su5FkvAF4KxqMXik';
    const SECRET_KEY = 'kVVSUgfc19vyDbg3FaL9nzIQyU0e6GPR';

//setConnectionTimeoutInMillis	建立连接的超时时间（单位：毫秒)
//setSocketTimeoutInMillis	通过打开的连接传输数据的超时时间（单位：毫秒）
    protected $setConTimeOut = 5000;        //ms建立连接的超时时间（单位：毫秒)
    protected $setSocTimeOut = 5000;        //ms通过打开的连接传输数据的超时时间（单位：毫秒）

    public function __construct()
    {

    }

    public function test(Request $request)
    {
        dd($request->get('user_msg'));
    }

    /**
     * 天气接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function weather(Request $request)
    {
        $curl =  'http://www.weather.com.cn/data/sk/101010100.html';
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$curl);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);
dd($output);
        $output = json_decode($output,true);
//        return $output;
//        dd($output);
        return response_success($output);

    }
    public function voiceInter(Request $request)
    {
        //获取相应的参数
        $params['headers'] = [
            'X-Appid' => '',
            'X-CurTime' => '',
            'X-Param' => '',
            'X-CheckSum' => '',
        ];
        $params['base_uri'] = 'http://api.xfyun.cn';
        $client = new Client();
        $client->get('/v1/service/v1/iat')->getBody();
    }

    public function bd_go(Request $request)
    {
        dd(getUser($request));
        return response_success(['message' => 222]);
        //setConnectionTimeoutInMillis	建立连接的超时时间（单位：毫秒)
        //setSocketTimeoutInMillis	通过打开的连接传输数据的超时时间（单位：毫秒）
        $apiOcr = new \AipOcr(self::APP_ID,self::API_KEY,self::SECRET_KEY);
        $image = file_get_contents(public_path('timg.jpg'));
        dd($image);

// 调用通用文字识别, 图片参数为本地图片
        $res = $apiOcr->basicGeneral($image);
        dd($res);
    }
}
