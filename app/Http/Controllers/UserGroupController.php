<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Home;

class UserGroupController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', '-1');
        $this->middleware('auth');

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

        if(!isset($access[4])):
            return redirect('/dashboard');
        endif;

        return view('admin.user_group.list', compact('menu','access','editable'));
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

        if(!isset($editable[4])):
            return redirect('/user_group');
        endif;

        $query = "";
        $user_group_id = "";
        $user_group = "";
        $access_list = Home::fetchAccess();
        $editable1 = [];
        $editable2 = [];

        $label = "Add";
        $label1 = "Create";

        return view('admin.user_group.create', compact('menu','query','label','label1','user_group_id','user_group','access_list','editable1','editable2'));
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
            'user_group'=>'required|unique:user_group,user_group'
        ]);

        Home::addUserGroup($request->all());

        return redirect('/user_group');


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

        if(!isset($editable[4])):
            return redirect('/user_group');
        endif;

        $query = Home::fetchUserGroupViaId($id);
        $user_group_id = $id;
        $user_group = $query->user_group;
        $access_list = Home::fetchAccess();
        $editable1 = array_flip(explode(",", $query->access));
        $editable2 = array_flip(explode(",", $query->editable));

        $label = "Update";
        $label1 = "Update";

        return view('admin.user_group.create', compact('menu','query','label','label1','user_group_id','user_group','access_list','editable1','editable2'));
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
            'user_group'=>'required|unique:user_group,user_group,'.$id
        ]);

        Home::updateUserGroup($request->all(), $id);

        return redirect('/user_group/'.$id.'/edit');
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
        $check = Home::fetchUserGroupViaId($id);

        if($check != ""):
            $check->user_group .= " (deleted)";
            $check->save();

            $check->delete();
        endif;
    }

    public function fetchUserGroup(){
        $query = Home::fetchUserGroup();

        $data = [];

        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($access[4])):
            $res = array('data'=>$data);
            return json_encode($res);
        endif;

        $link = URL('user_group');

        foreach($query as $result):

            $action = "";

            if(isset($editable[4])):

            $action .= "<a style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' title='Update' type='button' class='btn btn-info' href='$link/$result->id/edit'><i class='fas fa-edit'></i> Edit</a>";

                if(($result->id != 1 && $result->id != 2) && count($result->users) == 0):

                    $action .="<button style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' data-id='$result->id' style='border: 1px solid #b8c7ce; style='float: right;' class='btn btn-danger btn-flat btn-pri icon-delete'>
                            <i class='fas fa-trash'></i> Delete
                        </button>";

                endif;
            endif;

            $login = $result->creator->username != "" ? $result->creator->username : $result->creator->email;

            $data[] = array(
                $result->user_group,
                count($result->users),
                str_replace("N/A","",$result->creator->fullname)." (".$login.")",
                date("M d, Y h:i:s A",strtotime($result->created_at)),
                $action
                );

        endforeach;

        $res = array('data'=>$data);
        return json_encode($res);
    }
}
