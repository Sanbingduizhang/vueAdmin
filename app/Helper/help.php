<?php

if (!function_exists('response_success')) {
    /**
     * @param array $params
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    function response_success(Array $params=[],$status='successful',$code=1)
    {
        return response()->json([
            'status'=>$status,
            'code'=>$code,
            'data'=>$params
        ]);
    }
}
if (!function_exists('response_failed')) {
    /**
     * @param string $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
    function response_failed($message='Response Failed',$code=-1)
    {
        return response()->json(['status'=>'failed','code'=>$code,'message'=>$message]);
    }
}

if (!function_exists('getUser')) {
    /**获取用户信息
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    function getUser(\Illuminate\Http\Request $request) {
        return $request->get('user_msg');
    }
}
if (!function_exists('get_or_check_captcha')) {
    /**
     * 获取/检测验证码
     * @param $params
     * $param = [
                'width' => 250,
                'height' => 79,
                'font'  => null,
                'usercode' => '',
                'minu' => 5,
                'cachekey' => 'captcha_',
                'check' => '',
            ];
     * @param $type  string get/check  ---get(获取) ---check(检测)
     * @return bool|string
     */
    function get_or_check_captcha($params,$type) {
        $param = [
            'width' => 250,
            'height' => 79,
            'font'  => null,
            'usercode' => '',
            'minu' => 5,
            'cachekey' => 'captcha_',
            'check' => '',
        ];

        $paramarr = array_merge($param,$params);
        //根据传递的$type类型进行获取或者检测验证码
        if ($type == 'get') {
            $builder = new Gregwar\Captcha\CaptchaBuilder();
            //生成验证码图片的Builder对象，配置相应属性
            //可以设置图片宽高及字体
            $builder->build($width = 250, $height = 70, $font = null);
            //获取验证码的内容
            $phrase = $builder->getPhrase();
            //把内容存入session
            Illuminate\Support\Facades\Cache::put($paramarr['cachekey'].$paramarr['usercode'], $phrase,$paramarr['minu']);
            //保存路径
            $filename = storage_path().'/captchaimg/'.$paramarr['cachekey'].$paramarr['usercode'].'.jpg';
            $builder->save($filename);
            return $filename;

        } else if ($type = 'check') {
            //如果输入的验证码阿赫存储的验证码相等，返回true并删除对应验证码的缓存
            if (Illuminate\Support\Facades\Cache::get($paramarr['cachekey'].$paramarr['usercode']) == $paramarr['check']) {
                Illuminate\Support\Facades\Cache::forget($paramarr['cachekey'].$paramarr['usercode']);
                //用户输入验证码正确
                return true;
            }
            //用户输入验证码错误
            return false;
        }

        return false;
    }
}