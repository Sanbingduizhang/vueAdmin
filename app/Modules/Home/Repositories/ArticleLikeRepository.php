<?php

namespace App\Modules\Home\Repositories;


use App\Modules\Base\Repositories\BaseRepository;
use App\Modules\Home\Models\ArticleLikeModel;

class ArticleLikeRepository extends BaseRepository
{
    public  function model()
    {
        return ArticleLikeModel::class;
    }

}