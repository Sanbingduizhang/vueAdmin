<?php

namespace App\Modules\Home\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function test(Request $request)
    {
        dd($request->get('jwt'));
    }
}
