<?php

namespace App\Modules\Home\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class BdAiController extends Controller
{
//    protected $token_url = 'https://openapi.baidu.com/oauth/2.0/token';
//    const APP_ID = '11689133';
//    const API_KEY = 'y3XQLsq5su5FkvAF4KxqMXik';
//    const SECRET_KEY = 'kVVSUgfc19vyDbg3FaL9nzIQyU0e6GPR';


    const APP_ID = '11751282';
    const API_KEY = 'GTNNpcTL40bWLTBIyXQ3jSXf';
    const SECRET_KEY = 'BrdgBLGTMUjaNd2NUNxlRQvrVbaBt7dr';

    protected $token;

//setConnectionTimeoutInMillis	建立连接的超时时间（单位：毫秒)
//setSocketTimeoutInMillis	通过打开的连接传输数据的超时时间（单位：毫秒）
    protected $setConTimeOut = 5000;        //ms建立连接的超时时间（单位：毫秒)
    protected $setSocTimeOut = 5000;        //ms通过打开的连接传输数据的超时时间（单位：毫秒）

    public function __construct()
    {
        $this->token = $this->getToken();
    }

    /**
     * 获取语音所需的token
     */
    public function getToken()
    {
//        $token = Cache::get(self::API_KEY);
//        if (!$token) {
            $url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=y3XQLsq5su5FkvAF4KxqMXik&client_secret=kVVSUgfc19vyDbg3FaL9nzIQyU0e6GPR&";
            $tokenRes = $this->doGet($url);
            $token = json_decode($tokenRes,true)['access_token'];
//            Cache::put(self::API_KEY,$token,60);
//        }
        return $token;
    }

    public function sperec()
    {

        $aipSpeech = new \AipSpeech(self::APP_ID,self::API_KEY,self::SECRET_KEY);
//        $result = $aipSpeech->synthesis('你好百度', 'zh', 1, array(
//            'vol' => 5,
//        ));
//        // 识别正确返回语音二进制 错误则返回json 参照下面错误码
//        if(!is_array($result)){
//            file_put_contents('audio.mp3', $result);
//        }
//        dd($res);
        $res = $aipSpeech->asr(file_get_contents(public_path('16k.pcm')),'pcm',16000);
        dd($res);
    }







    //////////------curl的get,post,delete---------////////
    /**
     * @param string $url
     * @return mixed
     */
    public function doGet($url)
    {
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//        curl_setopt($ch, CURLOPT_USERPWD, "{$this->name}:{$this->pwd}");
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

        return $output;
    }

    /**
     * @param string $url
     * @param string $post_data
     * @return mixed
     */
    public function doPost($url,$post_data,$type)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//        curl_setopt($ch, CURLOPT_USERPWD, "{$this->name}:{$this->pwd}");
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 设置请求方式为post
        curl_setopt($ch, CURLOPT_POST, true);
        // post的变量
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: $type"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 请求头，可以传数组
//        curl_setopt($ch, CURLOPT_HEADER, $header);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function doDel($url)
    {
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->name}:{$this->pwd}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
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

        return $output;

    }
}
