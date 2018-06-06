<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Route;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\ServerBag;


class TestController extends Controller
{

/*
public function charu(){
    $arr = get_arr();
    $fen = get_fen1($arr);
    if($fen<5){
        $re = json_encode($arr);
        $rel = DB::table('xiongmao_lose')->where('arr',$re)->get();
        if(!$rel){
            $res = DB::table('xiongmao_lose')->insert(['arr'=>$re]);
        }
    }
}

public function test(){
    for($i=1;$i<=10000;$i++){
        $this->charu();
    }
    echo 'ok';
}

*/




}
