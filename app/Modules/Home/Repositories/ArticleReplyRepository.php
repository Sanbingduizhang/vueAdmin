<?php

namespace App\Modules\Home\Repositories;


use App\Modules\Base\Repositories\BaseRepository;
use App\Modules\Home\Models\ArticleReplyModel;

class ArticleReplyRepository extends BaseRepository
{
    public  function model()
    {
        return ArticleReplyModel::class;
    }

}