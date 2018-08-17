<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoLikeModel extends Model
{
    protected $table = 'photo_likecount';
    protected $guarded = ['id'];
}
