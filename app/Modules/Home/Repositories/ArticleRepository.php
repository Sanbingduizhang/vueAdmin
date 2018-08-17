<?php

namespace App\Modules\Home\Repositories;


use App\Modules\Base\Repositories\BaseRepository;
use App\Modules\Home\Models\ArticleModel;

class ArticleRepository extends BaseRepository
{
    public  function model()
    {
        return ArticleModel::class;
    }

}