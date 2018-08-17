<?php

namespace App\Modules\Home\Repositories;


use App\Modules\Base\Repositories\BaseRepository;
use App\Modules\Home\Http\Requests\CategoryRequest;
use App\Modules\Home\Models\CategoryModel;
use Illuminate\Http\Request;

class CateRepository extends BaseRepository
{
    public  function model()
    {
        return CategoryModel::class;
    }

    /**
     * 获取创建分类的数据并设置默认值
     * @param Request $request
     * @return array
     */
    public function createGet(Request $request)
    {
        $options = [
            'name' => $request->get('name',''),
            'pid' => $request->get('pid',0),
            'desc' => $request->get('desc',''),
        ];
        return $options;
    }
    public function editGet(Request $request)
    {
        $options = [
            'name' => $request->get('name',''),
            'pid' => $request->get('pid',0),
            'desc' => $request->get('desc',''),
        ];
    }

}