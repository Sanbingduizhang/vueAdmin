<?php

namespace App\Modules\Test\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class WastonTestController extends Controller
{
    protected $newWaston;

    public function __construct()
    {
        $params = [
            'base_uri' => 'https://gateway.watsonplatform.net/assistant/api/v1',
            'version' => 'version=2018-07-10',
            'name' => 'b45b1ce6-3f1d-4dd7-a744-1a64a33dd862',
            'pwd' => 'QBRv8rU7oWIb',
        ];
        $this->newWaston = new \Waston($params);
    }


//70eb18f9-e6d1-4eb2-8681-74bd09ad06a5

    public function getWorkspace()
    {
        $res = $this->newWaston->getOneIntents(['workspaces' => '70eb18f9-e6d1-4eb2-8681-74bd09ad06a5','intents' => 'people',]);
//        dd($res);
        return response_success($res);
    }
}
