<?php

namespace App\Modules\Home\Http\Requests;

use App\Modules\Base\Http\Requests\ApiBaseRequest;

class CategoryRequest extends ApiBaseRequest
{
    protected $rules = [
        'name' => 'required|string|max:10',
        'desc' => 'nullable|string|max:50',
        'pid'  => 'nullable|numeric',
    ];

    protected $messages = [
        'name.required' => '分类名称必填',
        'pid.numeric' => 'pid必须是数字int型',
    ];
}
