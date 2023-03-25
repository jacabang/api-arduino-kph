<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Home;

class KwphController extends Controller
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

    public function index()
    {
        //
        $menu = Home::menu();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($access[9])):
            return redirect('/');
        endif;

        $kwph_id = "";
        $label1 = $label = "Update";

        $kwph = Home::fetchKwphLatest();

        return view('admin.kwph.list', compact('menu','access','editable','kwph','kwph_id','label1','label'));
    }

    public function fetchKwph()
    {
        //
        $data = [];

        $query = Home::fetchUsers();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($access[9])):
            $res = array('data'=>$data);
            return json_encode($res);
        endif;

        $link = URL('kwph');

        $query = Home::fetchKwph();

        foreach($query as $result):

            $login = $result->creator->username != "" ? $result->creator->username : $result->creator->email;

            $data[] = array(
                $result->kwph,
                $result->deleted_at == '' ? 'Active' : 'Deactivated',
                str_replace("N/A","",$result->creator->fullname)." (".$login.")",
                date("M d, Y h:i:s A",strtotime($result->created_at))

            );

        endforeach;

        $res = array('data'=>$data);
        return json_encode($res);
    }

    public function store(Request $request)
    {

        $kwph = Home::fetchKwphLatest();

        if($kwph != ""):

            if($request->get('kwph') == $kwph->kwph):

                $array[] = array(
                    "type" => "warning",
                    "message" => "Please update amount with a different value!",
                );
    
                return redirect('/kwph')->with('notification', $array);

            endif;

        endif;

        Home::createKwph($request->all());

        $array[] = array(
            "type" => "success",
            "message" => "New value amount has been set!",
        );

        return redirect('/kwph')->with('notification', $array);
    }
}
