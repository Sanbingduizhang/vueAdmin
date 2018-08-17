<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Home\Http\Requests\CategoryRequest;
use App\Modules\Home\Models\CategoryModel;
use App\Modules\Home\Repositories\CateRepository;
use Illuminate\Http\Request;


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

    /**
     * 获取单个分类数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $id = (int)$id;
        if (empty($id)) {
            return response_failed('请传入相关正确参数');
        }
        $findRes = $this->cateRepository->find($id);
        if ($findRes) {
            return response_success($findRes->toArray());
        }
        return response_success([]);
    }

    /**
     * 分类更新名称和说明
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(CategoryRequest $request)
    {
        //获取分类相关的数据
        $options = $this->cateRepository->editGet($request);
        if (empty($options['name'])) {
            return response_failed('请填写分类名称');
        }
        //先做查询操作
        $findRes = $this->cateRepository->find($options['id']);
        if (!$findRes) {
            return response_failed('查询数据有误');
        }

        //获取当前用户的id
        $userinfo = getUser($request);
        if ($userinfo['type'] > 1) {
            return response_failed('您没有权限操作');
        }
        //判断这条分类此人是否有权做相关操作
        if ($findRes->can_del == 1) {
            if ($userinfo['type'] == 1) {
                return response_failed('您没有权限操作');
            }
            $updateRes = $findRes->update($options);
            if ($updateRes) {
                return response_success(['message' => '更新成功']);
            }
            return response_failed('更新失败');
        }
        //更新操作
        $updateRes = $findRes->update($options);
        if ($updateRes) {
            return response_success(['message' => '更新成功']);
        }
        return response_failed('更新失败');
    }

    /**
     * 分类的删除操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request)
    {
        //获取要删除的数据 array
        $ids = $request->get('ids',[]);
        if (empty($ids) || !is_array($ids)) {
            return response_failed('请传入正确类型的参数');
        }
        //判断是否有权删除相关数据
        //获取当前用户的id
        $userinfo = getUser($request);
        if ($userinfo['type'] > 1) {
            return response_failed('您没有权限操作');
        }
        //不是超级管理员，有些分类无权操作
        if ($userinfo['type'] == 1) {
            $findRes = $this->cateRepository
                ->where(['can_del' => 1])
                ->whereIn('id',$ids)
                ->get();
            if (count($findRes) > 0) {
                return response_failed('请选择的分类中有您无权操作的内容');
            }
            $updateDel = $this->cateRepository->whereIn('id',$ids)->update(['is_del' => 2]);
            if ($updateDel) {
                return response_success(['message' => '删除成功']);
            }
            return response_failed('删除失败');
        }
        //删除操作
        $updateDel = $this->cateRepository->whereIn('id',$ids)->update(['is_del' => 2]);
        if ($updateDel) {
            return response_success(['message' => '删除成功']);
        }
        return response_failed('删除失败');


    }
}
