<?php

use App\controllers\Controller;
use Core\Facades\Auth;
use Core\Facades\Request;
use Core\Facades\Validation\Validator;

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

        $this->model('Users')->insert($data);

        return redirect('/');
  	}
}
 ?>
