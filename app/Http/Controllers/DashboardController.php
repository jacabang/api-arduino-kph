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

        $select = "treg as `year`, ";

        $select_array = array();

        $query1 = [];

        $query = DB::select("SELECT a.id, socket_name, device_name FROM (SELECT * FROM `socket` WHERE id IN (SELECT socket_id FROM socket_reading WHERE deleted_at IS NULL) AND deleted_at IS NULL) as a LEFT JOIN (SELECT * FROM device WHERE deleted_at IS NULL) as b ON a.device_id = b.id");

        $query1 = collect($query);

        if(count($query1->where('create_by', Auth::user()->id)) != 0):

            foreach($query as $result):

                $string = "SUM(CASE WHEN socket_id = {$result->id} THEN variance_kwh ELSE 0 END) as `{$result->id}`";
                array_push($select_array,$string);

            endforeach;

            $select .= implode(", ",$select_array);

            $sql = "
                SELECT 
                    {$select} 
                FROM
                    (SELECT treg, socket_id, variance_kwh FROM socket_reading WHERE deleted_at IS NULL) as a

                GROUP BY `year` ORDER BY `year` ";

            $query1 = DB::select(DB::RAW($sql));

        endif;

        return view('admin.dashboard', compact('menu','editable','access','query','query1'));

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
