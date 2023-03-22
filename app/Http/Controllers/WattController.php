<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Home;


class WattController extends Controller
{
    //

    public function wattreading(Request $request){
        $data = Home::addSocketReading($request->all());

        return json_encode($data);
    }

    public function fetchSocket(Request $request){
        $data = Home::fetchAvailableSocket();

        $res = array(
            'data' => $data,
            'response' => 200
        );

        return json_encode($res);
    }
}
