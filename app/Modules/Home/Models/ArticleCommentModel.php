<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCommentModel extends Model
{
    protected $table = 'article_comment';
    protected $guarded = ['id'];
}
