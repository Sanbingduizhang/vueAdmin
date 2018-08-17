<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Home\Http\Requests\CategoryRequest;
use App\Modules\Home\Models\CategoryModel;
use App\Modules\Home\Repositories\CateRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class CategoryController extends ApiBaseController
{
    protected $cateRepository;

    public function __construct(CateRepository $cateRepository)
    {
        $this->cateRepository = $cateRepository;
    }

    /**
     * 获取所有文章分类
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cateRes = $this->cateRepository
            ->select(['id','name'])
            ->where([
                'is_del' => CategoryModel::DEL_STATUS_NO,
                'pid' => CategoryModel::CATE_PARENT_PID,
            ])
            ->limit(6)
            ->get()->toArray();
        return response_success($cateRes);

    }

    /**
     * 创建分类列表显示
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CategoryRequest $request)
    {
        //获取分类相关的数据
        $options = $this->cateRepository->createGet($request);
        if (empty($options['name'])) {
            return response_failed('请填写分类名称');
        }
        //获取当前用户的id
        $options['id'] = getUser($request)['id'];
        //创建数据
        $insertRes = $this->cateRepository->create($options);
        if ($insertRes) {
            return response_success(['message' => '创建成功']);
        }
        return response_failed('创建失败');
    }
    public function edit()
    {

    }
}
