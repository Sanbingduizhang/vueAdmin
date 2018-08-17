<?php

namespace App\Modules\Home\Repositories;


use App\Modules\Base\Repositories\BaseRepository;
use App\Modules\Home\Models\ArticleCommentModel;

class ArticleComRepository extends BaseRepository
{
    public  function model()
    {
        return ArticleCommentModel::class;
    }

}