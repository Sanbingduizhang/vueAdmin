<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class HomeController extends ApiBaseController
{
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

        /**
         * @param string $url
         * @return mixed
         */
//        $curl = $request->get('url',NULL);
//        if (empty($curl)) {
//            return response_failed('请输入地址');
//        }
//        $curl = 'https://www.sojson.com/open/api/weather/json.shtml?city=北京';
        $curl = 'http://www.weather.com.cn/data/cityinfo/101010100.html';
        http://www.weather.com.cn/data/sk/cityinfo/101010100.html
//
//        http://www.weather.com.cn/data/cityinfo/101010100.html
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

//        $output = json_decode($output,true);
//        return $output;
        dd($output);
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

}
