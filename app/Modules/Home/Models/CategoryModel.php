<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    const DEL_STATUS_YES = 0;  //未删除
    const DEL_STATUS_NO = 1;  //已经删除
    const CATE_PARENT_PID = 0;  //父ID
    const CATE_CAN_DEL_YES = 0;  //可以删除
    const CATE_CAN_DEL_NO = 1;  //不可以删除

    protected $table = 'category';
    protected $guarded = ['id'];
}
