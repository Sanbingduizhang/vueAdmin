<?php

namespace App\Modules\Home\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BdAiController extends Controller
{
    //文字识别
    const WOR_APP_ID = '11689133';
    const WOR_API_KEY = 'y3XQLsq5su5FkvAF4KxqMXik';
    const WOR_SECRET_KEY = 'kVVSUgfc19vyDbg3FaL9nzIQyU0e6GPR';

    //语音apk
    const SPE_APP_ID = '11751282';
    const SPE_API_KEY = 'GTNNpcTL40bWLTBIyXQ3jSXf';
    const SPE_SECRET_KEY = 'BrdgBLGTMUjaNd2NUNxlRQvrVbaBt7dr';

    //通用apk
    const APP_ID = '11753599';
    const API_KEY = 'WkzGDfC7d0HGSAOrGXRlT3i9';
    const SECRET_KEY = '3GX4h6Vc33y1zdfophY39YGSjeKmQYrZ ';

    protected $returnMag = [
        0 => '文件上传时出错',
        1 => '文件格式不正确，请上传正确的文件类型',
        2 => '文件保存时出错',
    ];

//setConnectionTimeoutInMillis	建立连接的超时时间（单位：毫秒)
//setSocketTimeoutInMillis	通过打开的连接传输数据的超时时间（单位：毫秒）
    protected $setConTimeOut = 5000;        //ms建立连接的超时时间（单位：毫秒)
    protected $setSocTimeOut = 5000;        //ms通过打开的连接传输数据的超时时间（单位：毫秒）

    public function __construct()
    {

    }

    /**
     * 返回-1，-2，-3相关的数据
     * @param $options
     * @return array
     */
    public function returnMsg($options = [])
    {
        return array_merge($this->returnMag,$options);
    }

    //////////-------语音方面-------/////////////
    /**
     * 语音合成----小于1kb（1kb相当于512个汉字，此处只允许500个以内的汉字）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function spesyn(Request $request)
    {
        //其他相应的参数
        $options = [
          'spd' => $request->get('spd',5),  //语速，取值0-9，默认为5中语速	否
          'pit' => $request->get('pit',5),  //音调，取值0-9，默认为5中语调	否
          'vol' => $request->get('vol',5),  //默认为5中音量	否
          'per' => $request->get('per',0),  //发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女否
        ];

        $words = $request->get('words','');
        if (empty($words)) {
            return response_failed('请输入相应的文字');
        }
        //此处仅允许500个字符
        $strlen = mb_strlen($words,'utf-8');
        if ($strlen > 500) {
            return response_failed('您已超出字数限制，500以内即可');
        }

        //调用百度接口
        $aipSpeech = new \AipSpeech(self::APP_ID,self::API_KEY,self::SECRET_KEY);
        $result = $aipSpeech->synthesis($words, 'zh', 1, $options);

        // 识别正确返回语音二进制
        if(!is_array($result)){
            $audio = uniqid() . 'audio.mp3';
            file_put_contents($audio, $result);
            return response_success(['path' => public_path($audio)]);
        }
        return response_failed($result);
    }

    /**
     * 语音识别----时间不得超过60s---10M以内
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sperec(Request $request)
    {
        //上传文件验证
        $files = uploadsFile($request,['pcm','wav','amr']);
        //判断返回数据
        if ($files == 0 || $files == 1 || $files == 2) {
            $failed = $this->returnMsg([ 1 =>'文件类型错误，请上传pcm,wav,amr格式的文件']);
            return response_failed($failed[$files]);
        }
        //调用百度接口
        $aipSpeech = new \AipSpeech(self::APP_ID,self::API_KEY,self::SECRET_KEY);
        $res = $aipSpeech->asr(file_get_contents($files['path']),$files['ext'],16000);
        //根据结果返回相应的数据
        if ($res['err_no'] == 0) {
            return response_success($res['result']);
        }
        return response_failed($res);
    }

    ////////////-文字方面--------------////////////////

    /**
     * 文字识别
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function worrec(Request $request)
    {
        $uploadimg = uploadsFile($request,['png','jpg','jpeg','bmp']);
        if ($uploadimg == 0 || $uploadimg == 1 || $uploadimg == 2) {

            $failed = $this->returnMsg([ 1 =>'文件类型错误，请上传png,jpg,jpeg,bmp格式的文件']);
            return response_failed($failed[$uploadimg]);
        }
        //调用百度接口
        $apiOcr = new \AipOcr(self::APP_ID,self::API_KEY,self::SECRET_KEY);
        $image = file_get_contents(storage_path('app/local/'.$uploadimg['name']));

        // 调用通用文字识别, 图片参数为本地图片
        $res = $apiOcr->basicAccurate($image);
        //删除对应图片
        Storage::disk('deletef')->delete($uploadimg['name']);

        if (isset($res['error_code'])) {
            return response_failed($res);
        }
        return response_success($res);
    }

    //////////-----图片识别------////////

    /**
     * 图片识别----因为判断动物或者植物，所以此处使用通用识别接口
     * todo 如果有相应要求，可以更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function imgrec(Request $request)
    {
        $uploadimg = uploadsFile($request,['png','jpg','jpeg','bmp']);
        if ($uploadimg == 0 || $uploadimg == 1 || $uploadimg == 2) {

            $failed = $this->returnMsg([ 1 =>'文件类型错误，请上传png,jpg,jpeg,bmp格式的文件']);
            return response_failed($failed[$uploadimg]);
        }

        $aipimage = new \AipImageClassify(self::APP_ID,self::API_KEY,self::SECRET_KEY);

        $image = file_get_contents(storage_path('app/local/'.$uploadimg['name']));
        //通用接口调用
        $res = $aipimage->advancedGeneral($image);
        //删除对应图片
        Storage::disk('deletef')->delete($uploadimg['name']);
        if (isset($res['error_code'])) {
            return response_failed($res);
        }
        return response_success($res);

    }

    /**
     * 人脸检测接口-----暂时无用的接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * ['face_field'=>'age,beauty,expression,face_shape,gender,glasses,landmark,race,quality,face_type']
     */
    public function facdet(Request $request)
    {
        $uploadimg = uploadsFile($request,['png','jpg','jpeg','bmp']);
        if ($uploadimg == 0 || $uploadimg == 1 || $uploadimg == 2) {

            $failed = $this->returnMsg([ 1 =>'文件类型错误，请上传png,jpg,jpeg,bmp格式的文件']);
            return response_failed($failed[$uploadimg]);
        }
        $image = file_get_contents(storage_path('app/local/'.$uploadimg['name']));

        //调用接口，返回年龄，人种，性别等信息
        $aipface = new \AipFace(self::APP_ID,self::API_KEY,self::SECRET_KEY);
        $res = $aipface->detect(base64_encode($image),'BASE64',[
            'face_field' => 'age,beauty,expression,face_shape,gender,glasses,race,face_type',
        ]);
        //删除对应图片
        Storage::disk('deletef')->delete($uploadimg['name']);
        if ($res['error_code'] == 0) {
            return response_success($res);
        }
        return response_failed($res);
    }

    /**
     * 人脸对比
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function faccon(Request $request)
    {
        $uploadimg = uploadsFile($request,['png','jpg','jpeg','bmp']);
        if ($uploadimg == 0 || $uploadimg == 1 || $uploadimg == 2) {

            $failed = $this->returnMsg([ 1 =>'文件类型错误，请上传png,jpg,jpeg,bmp格式的文件']);
            return response_failed($failed[$uploadimg]);
        }
        //判断是否是多张
        $imageArr = [];
        if (count($uploadimg) <= 1) {
            return response_failed('请上传至少两张有人脸的图片');
        }
        //遍历组合数组
        foreach ($uploadimg as $key => $val) {
            $imageArr[] = [
                "image" => base64_encode(file_get_contents(storage_path('app/local/'.$val['name']))),
                "image_type" => "BASE64",
                "face_type" => "LIVE",
                "quality_control" => "LOW",
                "liveness_control" => "NONE"
            ];
            //删除对应图片
            Storage::disk('deletef')->delete($val['name']);
        }
        //调用接口进行对比
        $aipface = new \AipFace(self::APP_ID,self::API_KEY,self::SECRET_KEY);
        $res = $aipface->match($imageArr);



        if ($res['error_code'] == 0) {
            return response_success($res);
        }
        return response_failed($res);
    }
}
