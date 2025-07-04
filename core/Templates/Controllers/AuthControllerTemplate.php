<?php

namespace App\Controllers\Auth;

use App\controllers\Controller;
use Core\Support\Auth;
use Core\Support\Request;
use Core\Support\Validation\Validator;

class controllername extends Controller
{
    public function __construct(){
        //
    }

    public function index()
    {
        return view('auth/login');
    }

  	public function login(Request $request)
  	{
        $validation = Validator::validate($request->all(),[
            'email' => 'required|mail',
            'password' => 'required',
        ]);

        if($validation->fails()){
            return redirect()->backWithErrors($validation->errors());
        }

        if (Auth::attempt(['email'=>$request->post('email'),'password'=>$request->post('password')])){
            return redirect('/');
        } else {

            return redirect()->backWith('error','credentials did not match with our record');
        }
  	}

    public function logout() {
        Auth::logout();
        return redirect('login');
    }

}
