<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Repositories\UserInfoRepository;
use App\Modules\Home\Models\ArticleModel;
use App\Modules\Home\Models\ArticleReplyModel;
use App\Modules\Home\Models\CategoryModel;
use App\Modules\Home\Repositories\ArticleComRepository;
use App\Modules\Home\Repositories\ArticleLikeRepository;
use App\Modules\Home\Repositories\ArticleReplyRepository;
use App\Modules\Home\Repositories\ArticleRepository;
use App\Modules\Home\Repositories\CateRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    protected $articleLikeRepository;
    protected $articleComRepository;
    protected $articleRepository;
    protected $userInfoRepository;
    protected $cateRepository;

    public function __construct(
        ArticleLikeRepository $articleLikeRepository,
        ArticleComRepository $articleComRepository,
        ArticleRepository $articleRepository,
        UserInfoRepository $userInfoRepository,
        CateRepository $cateRepository)
    {
        $this->articleLikeRepository = $articleLikeRepository;
        $this->articleComRepository = $articleComRepository;
        $this->articleRepository = $articleRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->cateRepository = $cateRepository;
    }

    /**
     * 主页显示---中间
     */
    public function index_mid()
    {
        $this->articleRepository
            ->with(['Cate' => function ($c) {
                $c->where(['is_del' => CategoryModel::DEL_STATUS_NO]);
            }])
            ->with([''])
            ->where([
                'is_del'    => ArticleModel::IS_DEL_NO,
                'status'    => ArticleModel::PUBLISH_YES,
                'publish'   => ArticleModel::PUBLISH_YES,
                'is_pv_use' => ArticleModel::PV_USE_ALL,
            ]);


    }

    /**
     * 右边
     */
    public function index_right()
    {


    }

    /**
     * 获取头部信息--只获取六个
     * @return \Illuminate\Http\JsonResponse
     */
    public function index_head()
    {
        //查找所有分类进行显示
        //只显示未删除的--而且支取前六个
        $cateRes = $this->cateRepository
            ->where([
                'is_del' => CategoryModel::DEL_STATUS_NO
            ])
            ->limit(6)->get();
        if (count($cateRes) <= 0) {
            return response_success([]);
        }
        return response_success($cateRes->toArray());
    }
}
