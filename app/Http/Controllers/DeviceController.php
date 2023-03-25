<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Home;

class DeviceController extends Controller
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

        if(!isset($access[7])):
            return redirect('/');
        endif;

        return view('admin.device.list', compact('menu','access','editable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $menu = Home::menu();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($editable[7])):
            return redirect('/device');
        endif;

        $query = "";
        $device_id = "";
        $device_name = "";
        $device_code = "";

        $label = "Add";
        $label1 = "Create";

        return view('admin.device.create', compact('menu','query','label','label1','device_id','device_name','device_code','access','editable'));
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

        $this->validate($request, [
            'device_name'=>'required|unique:device,device_name',
        ]);

        Home::addDevice($request->all());

        return redirect('/device');
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
        $menu = Home::menu();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($editable[7])):
            return redirect('/device');
        endif;

        $query = Home::fetchDeviceViaId($id);

        if($query == ""):
            return redirect('/device');
        endif;

        $device_id = $id;
        $device_name = $query->device_name;
        $device_code = $query->device_code;

        $label = "Update";
        $label1 = "Update";

        return view('admin.device.create', compact('menu','query','label','label1','device_id','device_name','device_code','access','editable'));
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
        $this->validate($request, [
            'device_name'=>'required|unique:device,device_name,'.$id,
        ]);

        Home::updateDevice($request->all(), $id);

        return redirect('/device/'.$id.'/edit');
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
        $query = Home::fetchDeviceViaId($id);

        if($query != ""):

            foreach($query->socket as $result):
                $result->socket_name .= " (DELETED)";
                $result->save();
                foreach($result->readings as $result1):
                    $result1->delete();
                endforeach;
                $result->delete();
            endforeach;

            $query->device_name .= " (DELETED)";
            $query->device_code .= " (DELETED)";
            $query->save();

            $query->delete();

        endif;
    }

    public function fetchDevice(){
        $query = Home::fetchDevice();

        $data = [];

        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($access[7])):
            $res = array('data'=>$data);
            return json_encode($res);
        endif;

        $link = URL('device');

        foreach($query->where('created_by', Auth::user()->id) as $result):

            $variance = 0;

            $action = "";

            $order_view = '<table class="table table-bordered table-striped mb-none">
                <thead
                    <tr>
                    <th>Socket Name</th>
                    <th>Socket Code</th>
                    <th>Current KHW</th>
                    <th>Total Bill</th>
                    <th>Last Reading Date</th>
                    </tr>
                </thead>
                <tbody>
                ';

            $total_price = 0;

            foreach($result->socket as $result1):
                $variance += $result1->current_kwh;
                $last_reading = $result1->reading != '' ? $result1->reading->treg : '';

                $price = 0;

                foreach($result1->readings as $result2):
                    $price += $result2->kwph * $result2->variance_kwh;
                endforeach;

                $total_price += $price;

                $order_view .= "<tr>
                    <td>$result1->socket_name</td>
                    <td>$result1->socket_code</td>
                    <td>$result1->current_kwh</td>
                    <td>$price</td>
                    <td>$last_reading</td>
                    </tr>";
            endforeach;

            $order_view ."</tbody></table>";
            

            if(isset($editable[7])):

                $action .= "<a style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' title='Update' type='button' class='btn btn-info' href='$link/$result->id/edit'><i class='fas fa-edit'></i> Edit</a>";

                if($variance == 0):

                $action .="<button style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' data-id='$result->id' style='border: 1px solid #b8c7ce; style='float: right;' class='btn btn-danger btn-flat btn-pri icon-delete'>
                                <i class='fas fa-trash'></i> Delete
                            </button>";

                endif;

            endif;

            $login = $result->creator->username != "" ? $result->creator->username : $result->creator->email;

            $data[] = array(
                "oink" => "",
                "device_name"=>$result->device_name,
                "device_code" => $result->device_code,
                "current_kwh"=> $result->socket->sum('current_kwh'),
                "total_price"=> $total_price,
                "count"=> count($result->socket),
                "created_at"=>date("M d, Y H:i:s", strtotime($result->created_at)),
                "created_by"=>$result->creator->fullname."<br>(". $result->creator->email.")",
                "action"=>$action,
                "product"=>$order_view
            );

        endforeach;

        $res = array('data'=>$data);
        return json_encode($res);
    }
}
