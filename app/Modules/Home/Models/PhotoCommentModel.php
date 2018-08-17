<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoCommentModel extends Model
{
    protected $table = 'photo_comment';
    protected $guarded = ['id'];
}
