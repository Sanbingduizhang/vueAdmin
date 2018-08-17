<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleLikeModel extends Model
{
    protected $table = 'article_likecount';
    protected $guarded = ['id'];
}
