<?php

namespace App\Modules\Base\Http\Controllers;

use App\Modules\Base\Repositories\UserInfoRepository;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    protected $timestamp;
    protected $userInfoRepository;
    public function __construct(UserInfoRepository $userInfoRepository)
    {
        $this->timestamp = time();
        $this->userInfoRepository = $userInfoRepository;
    }

    public function login(Request $request)
    {
        $usercode = htmlspecialchars($request->get('usercode',null));
        $pwd = htmlspecialchars($request->get('password',null));

        if (empty($usercode) || empty($pwd)) {
            return response_failed('请输入用户名或密码');
        }
        //入库查询
        $searchRes = $this->userInfoRepository
            ->where(['usercode' => $usercode,'password' => md5($pwd)])
            ->first();
        if (!$searchRes) {
            return response_failed('');
        }

        $token = $this->issueJwtToken([
            'uid'=>$searchRes->userid,
            'usercode'=>$searchRes->usercode,
            'name'=>$searchRes->name,
        ]);
        return response_success(['token' => $token]);

    }

    /**
     * @description 签发jwt token
     * @param array $userInfo 教学平台用户信息
     *
     * @Payload
     * |- iss (Issuer) Token签发者
     * |- sub (Subject) 主题
     * |- aud (Audience) 接收者
     * |- exp (Expiration Time) 过期时间-UNIX时间戳，必须大于签发时间
     * |- nbf (Not Before) 指定一个UNIX时间戳之前，此TOKEN是不可用的
     * |- iat (Issued At) 签发时间-UNIX时间戳
     * |- jti (JWT ID) Token唯一身份标识
     * |- uuid 教学平台用户标识
     * @return string
     */
    protected function issueJwtToken($userInfo)
    {
        $payload  = [
            'iss'=>config('jwt.iss'),
            'sub'=>config('jwt.sub'),
            'aud'=>config('jwt.aud'),
            'exp'=>$this->timestamp + config('jwt.exp'),
            'iat'=>$this->timestamp,
            'uid'=>$userInfo['uid'],
            'usercode'=>$userInfo['usercode'],
            'name'=>$userInfo['name'],
        ];
        $token = JWT::encode($payload,config('jwt.key'),config('jwt.alg'));
        return $token;
    }
}
