<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Repositories\UserInfoRepository;
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

    }
    public function index_right()
    {

    }
    public function index_head()
    {

    }
}
