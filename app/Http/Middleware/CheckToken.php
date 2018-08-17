<?php

namespace App\Http\Middleware;

use App\Modules\Base\Repositories\UserInfoRepository;
use Closure;
use Firebase\JWT\JWT;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckToken
{
    protected $userInfoRepository;
    public function __construct(UserInfoRepository $userInfoRepository)
    {
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $jwt_token = trim(str_replace('Bearer','',$token));
        try {
            $jwt_value = (array)JWT::decode($jwt_token, config('jwt.key'), [config('jwt.alg')]);
            $user = $this->get_user($jwt_value);
            $request->attributes->add(['user_msg'=>$user]);
        }catch (\InvalidArgumentException $e){
            return response_failed($e->getMessage());
        }catch (\UnexpectedValueException $e) {
            return response_failed('The Authorization field in the head does not exist');
        }catch (ExpiredException $e) {
            return response_failed($e->getMessage());
        }catch (SignatureInvalidException $e) {
            return response_failed($e->getMessage());
        }catch (BeforeValidException $e){
            return response_failed($e->getMessage());
        }catch (DomainException $e) {
            return response_failed($e->getMessage());
        }
        return $next($request);
    }
    /**
     * 获取用户信息并加入缓存
     * @param $param
     * @return array|mixed
     */
    public function get_user($param)
    {
        $cacheKey = config('basecache.user_info_get').$param['usercode'];
        $userinfo = Cache::get($cacheKey);
        if (empty($userinfo) || !is_array($userinfo)) {
            $userinfo = $this->userInfoRepository->where(['id' => $param['uid']])->first();
            if ($userinfo) {
                $userinfo = $userinfo->toArray();
            } else {
                $userinfo = [];
            }
            if (env('APP_DEBUG') == false) {
                Cache::put($cacheKey,$userinfo,30);
            }
            return $userinfo;
        }
        return $userinfo;

    }

}
