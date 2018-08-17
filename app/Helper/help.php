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