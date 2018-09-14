<?php

namespace App\Modules\Test\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class WastonTestController extends Controller
{
    protected $newWaston;

    public function __construct()
    {
        //才哥的密钥
//        $params = [
//                'base_uri' => 'https://gateway.watsonplatform.net/visual-recognition/api/v3',
//                'version' => 'version=2018-03-19',
//                'apikey' => 'O1tx-GlvVXW7wJouobqFphMhLi5hhiKGNijlmtLTkuen',
//        ];
        //我的密钥
        $params = [
            'base_uri' => 'https://gateway.watsonplatform.net/visual-recognition/api/v3',
            'version' => 'version=2018-03-19',
            'apikey' => 'W3InsithP67OyIO0qddpYpPVtcD6xcNQM-CMf5MUywL_',
        ];
        $this->newWaston = new \WastonImg($params);
    }
    public function test()
    {
        $res = $this->newWaston->ceshi();
        var_dump($res);exit();
        return response_success($res);
    }
    public function getany()
    {
        return response_success($_FILES['file']);
    }
}
