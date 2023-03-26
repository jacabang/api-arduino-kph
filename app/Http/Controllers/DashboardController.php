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

        $user_id = Auth::user()->id;

        $device = Home::fetchDevice();
        $device = count($device->where('created_by', Auth::user()->id));

        $query = DB::select("SELECT a.id, socket_name, device_name, IFNULL(bill,0) as bill FROM (SELECT * FROM `socket` WHERE deleted_at IS NULL  AND created_by = '{$user_id}') as a 
            INNER JOIN (SELECT * FROM device WHERE deleted_at IS NULL AND created_by = '{$user_id}') as b ON a.device_id = b.id
            LEFT JOIN (SELECT socket_id, SUM(kwph * variance_kwh) as bill FROM socket_reading WHERE deleted_at IS NULL GROUP BY socket_id) as c ON a.id = c.socket_id

            ");

        $query1 = collect($query);

        $socket = count($query1);
        $bill = $query1->sum('bill');

        if(count($query1) != 0):

            foreach($query1 as $result):

                $string = "SUM(CASE WHEN socket_id = {$result->id} THEN variance_kwh ELSE 0 END) as `{$result->id}`";
                array_push($select_array,$string);

            endforeach;

            $select .= implode(", ",$select_array);

            $sql = "
                SELECT 
                    {$select} 
                FROM
                    (SELECT treg, socket_id, variance_kwh FROM socket_reading WHERE deleted_at IS NULL ) as a

                GROUP BY `year` ORDER BY `year` ";

            $query1 = DB::select(DB::RAW($sql));

        endif;

        $data1 = [];

        $data3 = [];
        $data4 = [];
        $data5 = [];
        $total = 0;
        $average = 0;
        foreach($query1 as $key => $result):
            $data5[$result->year] = 0;
            $data2 = [];
            foreach($result as $key1 => $result1):
                
                if($result1 != 0):

                    if(!isset($data3[$key1])):

                        $check4 = Home::fetchDeviceSocketViaId($key1);

                        if($check4 != ""):
                            $data3[$key1] = 1;
                            $data4[] = $check4;
                        endif;
                    endif;

                    if($key1 != 'year'):
                        $data5[$result->year] += $result1;
                        $total += $result1;
                    endif;

                    $data2[$key1] = $result1;
                endif;
            endforeach;

            if(count($data2) > 1):
                $data1[] = $data2;
            endif;
        endforeach;

        if(count($data5) != 0):

            $average = round($total / count($data5),2);

        endif;

        $query1 = $data1;

        return view('admin.dashboard', compact('menu','editable','access','query','query1','bill','socket','device','data4','average'));

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
