<?php

namespace App\Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoModel extends Model
{
    protected $table = 'photo';
    protected $guarded = ['id'];
}
