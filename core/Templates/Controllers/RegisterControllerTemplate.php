<?php

namespace App\Controllers\Auth;

use App\controllers\Controller;
use App\Models\Users;
use Core\Support\Auth;
use Core\Support\Request;
use Core\Support\Validation\Validator;

class controllername extends Controller
  {
    public function __construct(){
        //
    }

    public function register(){

        return view('auth/register');
    }

  	public function save(Request $request){

        $validation = Validator::validate($request->all(),[
            'name' => 'required',
            'email' => 'required|mail',
            'password' => 'required',
        ]);

        if($validation->fails()){
            return redirect()->backwithErrors($validation->errors());
        }

        $data = array(
            'name'=>$request->post('name'),
            'email'=>$request->post('email'),
            'password'=> Auth::Hash($request->post('password')),
        );

        Users::insert($data);

        Auth::attempt(['email'=>$request->post('email'),'password'=>$request->post('password')]);

        return redirect('/');
  	}
}
