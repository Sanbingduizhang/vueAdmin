<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    protected $table = 'article';
    protected $guarded = ['id'];
}
