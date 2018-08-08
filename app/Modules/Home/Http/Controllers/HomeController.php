<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;


class HomeController extends ApiBaseController
{
    public function __construct()
    {

    }

    public function test(Request $request)
    {
        dd($request->get('user_msg'));
    }
}
