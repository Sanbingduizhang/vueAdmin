<?php

namespace App\Modules\Home\Models;

use App\Modules\Base\Models\UserInfo;
use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    const IS_DEL_NO = 1;//未删除
    const IS_DEL_YES = 0;//删除

    const PV_USE_ALL = 1;//所有人观看
    const PV_USE_ORDER = 2;//指定人观看
    const PV_USE_SELF = 3;//仅自己可见

    const PUBLISH_YES = 1;//发布
    const PUBLISH_NO = 2;//不发布

    const IS_REC_YES = 1;//推荐
    const IS_REC_NO = 2;//不推荐

    const STATUS_YES = 1;//审核通过
    const STATUS_NO = 2;//审核不通过

//    const

    protected $table = 'article';
    protected $guarded = ['id'];

    //关联categroy表
    public function Cate()
    {
        return $this->belongsTo(CategoryModel::class,'cateid','id');
    }
    //关联userinfo表
    public function Userinfo()
    {
        return $this->belongsTo(UserInfo::class,'userid','id');
    }
    //关联article_comment表
    public function ArticleComment()
    {
        return $this->hasMany(ArticleCommentModel::class,'id','articleid');
    }
    //关联article_likecount表
    public function ArticleLike()
    {
        return $this->hasMany(ArticleLikeModel::class,'id','pid');
    }

}
