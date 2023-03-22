<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Home;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        ini_set('max_execution_time', '-1');
        ini_set('memory_limit', '-1');
        $this->middleware('auth'); //admin
        // $this->middleware('guest');

        ini_set('post_max_size', '64M');
        ini_set('upload_max_filesize', '64M');

        date_default_timezone_set('Asia/Manila');

    }

    public function index(){
        $menu = Home::menu();
        $user_group = Auth::user()->user_group;
        $editable = array_flip(explode(",", $user_group->editable));
        $access = array_flip(explode(",", $user_group->access));

        return view('admin.dashboard', compact('menu','editable','access'));

    }

    public function myApi(){
        $menu = Home::menu();
        $user_group = Auth::user()->user_group;
        $editable = array_flip(explode(",", $user_group->editable));
        $access = array_flip(explode(",", $user_group->access));

        if(!isset($access[5])):
            return redirect('/');
        endif;

        return view('admin.swagger', compact('menu','editable','access'));
    }
}
