<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Home;

class UserController extends Controller
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

        if(!isset($access[3])):
            return redirect('/');
        endif;

        return view('admin.user.list', compact('menu','access','editable'));
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

        if(!isset($editable[3])):
            return redirect('/user');
        endif;

        $user_group = Home::fetchUserGroup();

        // $organization = Home::fetchOrganization();
        // $position = Home::fetchPosition();

        $user_id = "";
        $query = "";

        $label = "Add";
        $label1 = "Create";

        $image_file = 'noprof.png';
        $fname = '';
        $uname = '';
        $email = '';
        $user_group_id = '';
        // $position_id = '';
        // $organization_id = '';
        $contact = '';
       
        return view('admin.user.create', compact('menu','label','label1','query','fname','email','uname','user_id','image_file','user_group_id','user_group','access','editable','contact'));
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
        if($request->get('email') != ""):
            $this->validate($request, [
                'fname'=>'required',
                // 'email'=>'email|unique:users,email',
                'uname'=>'required|unique:users,username',
            ]);
        else:
            $this->validate($request, [
                'fname'=>'required',
                'uname'=>'required|unique:users,username',
            ]);
        endif;

        $name = NULL;

        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = 'uploads';
            $image->move($destinationPath, $name);
        } else {
            $name = NULL;
        }

        Home::createUser($request->all(), $name);

        return redirect('/user');
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
        $menu = Home::menu();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        if(!isset($access[3])):
            return redirect('/');
        endif;
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

        if(!isset($editable[3])):
            return redirect('/user');
        endif;

        $query = Home::fetchUserViaId($id);
        $user_group = Home::fetchUserGroup();

        if($query == ""):
            return redirect('/user');
        endif;

        $user_id = $id;

        $label = "Update";
        $label1 = "Update";

        $image_file = 'noprof.png';

        if($query->image_file != ""):
            $image_path = base_path()."/uploads/".$query->image_file;
            if(!file_exists($image_path)):
                $image_file = 'noprof.png';
            else:
                $image_file = $query->image_file;
            endif;
        endif;

        $fname = str_replace("N/A","",$query->fullname);
        $uname = $query->username;
        $email = $query->email;
        $user_group_id = $query->user_group_id;
        $position_id = $query->position_id;
        $organization_id = $query->organization_id;
        $contact = $query->contact;
       
        return view('admin.user.create', compact('menu','label','label1','query','fname','email','uname','user_id','image_file','user_group_id','user_group','access','editable','contact'));
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
        if($request->get('email') != ""):
            $this->validate($request, [
                'fname'=>'required',
                // 'email'=>'email|unique:users,email,'.$id,
                'uname'=>'required|unique:users,username,'.$id,
            ]);
        else:
            $this->validate($request, [
                'fname'=>'required',
                'uname'=>'required|unique:users,username,'.$id,
            ]);
        endif;

        $name = NULL;

        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = 'uploads';
            $image->move($destinationPath, $name);
        } else {
            $name = NULL;
        }

        Home::updateUser($request->all(), $name, $id);

        return redirect('/user/'.$id.'/edit');
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
        $check = Home::fetchUserViaId($id);

        if($check != ""):
            $check->delete();
        endif;
    }

    public function fetchUser(){

        $data = [];

        $query = Home::fetchUsers();
        $user_group = Auth::user()->user_group;
        $access = array_flip(explode(",", $user_group->access));
        $editable = array_flip(explode(",", $user_group->editable));

        if(!isset($access[3])):
            $res = array('data'=>$data);
            return json_encode($res);
        endif;

        $link = URL('user');

        foreach($query as $result):

            $action = "";

            if(isset($editable[3])):

                $action .= "<a style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' title='Update' type='button' class='btn btn-info' href='$link/$result->id/edit'><i class='fas fa-edit'></i> Edit</a>";

                if(Auth::user()->id != $result->id):

                            $action .="<button style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' data-id='$result->id' style='border: 1px solid #b8c7ce; style='float: right;' class='btn btn-danger btn-flat btn-pri icon-delete'>
                                <i class='fas fa-trash'></i> Delete
                                </button>";

                        $action .="<button style='min-width: 100px; margin-bottom: .5em; margin-left: .5em;' data-id='$result->id' style='border: 1px solid #b8c7ce; style='float: right;' class='btn btn-warning btn-flat btn-pri icon-reset'>
                                <i class='fas fa-redo'></i> Reset Password
                            </button>";
                    
                endif;
            endif;

            $login = $result->creator->username != "" ? $result->creator->username : $result->creator->email;

            $data[] = array(
                str_replace("N/A","",$result->fullname),
                $result->email,
                $result->username,
                $result->user_group->user_group,
                str_replace("N/A","",$result->creator->fullname)." (".$login.")",
                date("M d, Y h:i:s A",strtotime($result->created_at)),
                $action
                );

        endforeach;

        $res = array('data'=>$data);
        return json_encode($res);
    }

    public function resetPassword($id){
        $user = Home::resetPassword($id);
    }

    public function editProfile(){
        $user = Auth::user();
        $menu = Home::menu();
        $user_id = Auth::user()->id;
        $label = "Update";
        $label1 = "Update";

        $query = Home::fetchUserViaId($user_id);

        if($query == ""):
            return redirect('/user');
        endif;

        $image_file = 'noprof.png';

        if($query->image_file != ""):
            $image_path = base_path()."/uploads/".$query->image_file;
            if(!file_exists($image_path)):
                $image_file = 'noprof.png';
            else:
                $image_file = $query->image_file;
            endif;
        endif;
        
        $fname = str_replace("N/A","",$query->fullname);
        $uname = $query->username;
        $email = $query->email;

        return view('admin.user.editProfile', compact('menu','label','label1','query','fname','email','uname','user_id','image_file'));
    }

    public function editProfileChange(Request $request){

        $old_password = $request->get('old_password');

        $this->validate($request, [
            'fname'=>'required',
            // 'email'=>'email|unique:users,email,'.Auth::user()->id,
            'uname'=>'required|unique:users,username,'.Auth::user()->id,
        ]);

        $name = NULL;

        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = 'uploads';
            $image->move($destinationPath, $name);
        } else {
            $name = NULL;
        }

        Home::updateUser($request->all(), $name, Auth::user()->id);

        if($old_password != ""):
            $this->validate($request, [
                'old_password' => 'required',
                'password' => 'required|confirmed|min:6'
            ]);

        $user = Auth()->user();

        if(!Hash::check($request->get('old_password'), $user->password)){
             return redirect()->back()->with('error', ['The specified old password does not match the database password']);  
        }else{
           // write code to update password

            $user->password = bcrypt($request->get('password'));
            $user->save();

            return redirect()->back()->with('success', ['Change Password Complete']);  
        }

        else:

            return redirect()->back();

        endif;
    }
}
