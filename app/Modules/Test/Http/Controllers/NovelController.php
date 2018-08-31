<?php

namespace App\Modules\Test\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class NovelController extends Controller
{
    public function getContent()
    {
        return response_success(['message' => 22]);
//        header('Access-Control-Allow-Origin', config('cors.allowedOrigins'));
        header('Access-Control-Allow-Origin:*');
        $html = file_get_contents("https://www.zhuaji.org/read/848");
//        dd(strrpos($html,'<div class="read-content j_readContent">'));
//        $ss = strrpos($html,'<div class="read-content j_readContent">') + 1;
//        $en = strrpos($html,'<div class="admire-wrap">') - $ss;
//       $text = substr($html,$ss,$en);
//        dd(strrpos($html,'<div class="admire-wrap">'));
//        file_put_contents()
        dd($html);

    }
}
