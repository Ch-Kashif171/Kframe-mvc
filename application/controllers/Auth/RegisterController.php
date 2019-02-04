<?php

use App\controllers\Controller;
use Core\Facades\Auth;
use Core\Facades\Request;
use Core\Facades\Captcha;
use Core\Facades\Validation\Validator;

class RegisterController extends Controller
  {
    public function __construct(){
        //
    }

    public function register(){

        $data['captcha'] = Captcha::get();
        return view('auth/register',$data);
    }

  	public function save(Request $request){

        $validation = Validator::validate($request->all(),[
            'name' => 'required',
            'email' => 'required|mail',
            'password' => 'required',
            'captcha' => 'required',
        ]);

        if($validation->fails()){
            return redirect()->backWithErrors($validation->errors());
        }

        if (! Captcha::verify($request->post('captcha'))){
            return redirect()->backWith('error','captcha did not match, please try again!');
        }

        $data = array(
            'name'=>$request->post('name'),
            'email'=>$request->post('email'),
            'password'=> Auth::Hash($request->post('password')),
        );

        $this->model('Users')->create($data);

        return redirect('login');
  	}
}
 ?>
