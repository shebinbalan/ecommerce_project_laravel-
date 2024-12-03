<?php

namespace App\Http\Controllers;
use Hash;
use Illuminate\Http\Request;
use Auth;
class AuthController extends Controller
{
    public function login_admin()
    {
        if(!empty(Auth::check()) && Auth::user()->is_admin == 1)
        {
            return redirect('admin/dashboard');
        }
        //dd(Hash::make(123456));
        return view('admin.auth.login');
    }
    public function auth_login_admin(Request $request)
    {
      $remember =!empty($request->remember) ? true :false;
      if(Auth::attempt(['email'=>$request->email,'password'=>$request->password,'is_admin'=>1,'status'=>0,'is_delete'=>0],$remember))
      {
        //dd(Hash::make(123456));
        return redirect('admin/dashboard');
      }
      else
      {
        return redirect()->back()->with('error',"Please Enter correct email and Password");
      }
    }

    public function logout_admin()
    {
        Auth::logout();
        return redirect('admin');
    }
}
