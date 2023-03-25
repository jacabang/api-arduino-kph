<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Home;

class RecordController extends Controller
{

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $menu = Home::menu();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($access[8])):
            return redirect('/');
        endif;

        return view('admin.record.list', compact('menu','access','editable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $check = Home::fetchSocketRecordViaId($id);
        if($check != ""):
            $check->socket->current_kwh -= $check->variance_kwh;
            $check->socket->save();
            $check->delete();
        endif;
    }



    public function fetchRecord(){

        $data = [];
        $checker = Home::fetchLatestReading();
        $query = Home::fetchReading();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($access[8])):
            $res = array('data'=>$data);
            return json_encode($res);
        endif;

        $link = URL('records');

        foreach($query as $result):

            $action = "";

            if(isset($editable[8])):

                if(count($checker->where('treg', $result->treg)->where('socket_id', $result->socket_id)) != 0):

                            $action .="<button style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' data-id='$result->id' style='border: 1px solid #b8c7ce; style='float: right;' class='btn btn-danger btn-flat btn-pri icon-delete'>
                                <i class='fas fa-trash'></i> Delete
                                </button>";
                    
                endif;
            endif;

            $data[] = array(
                $result->socket->device->device_name,
                // $result->socket->device->device_code,
                $result->socket->socket_name,
                // $result->socket->socket_code,
                date("M d, Y", strtotime($result->treg)),
                $result->variance_kwh,
                $result->kwph,
                $result->kwh,
                $result->variance_kwh * $result->kwh,
                $action
            );

        endforeach;

        $res = array('data'=>$data);
        return json_encode($res);
    }
}
