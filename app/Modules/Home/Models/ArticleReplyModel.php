<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReplyModel extends Model
{
    protected $table = 'article_reply';
    protected $guarded = ['id'];
}
