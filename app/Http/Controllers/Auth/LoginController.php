<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login_page()
    {
      return view('auth.login');
    }
     
    public function login(Request $request){
      $fields = $request->validate([
        "email" => "required|email",
        "password" => "required"
    ]);

    if(Auth::attempt(['email' => $fields['email'], "password" => $fields['password']])){
        return to_route('dashboard')->with("success", "You have logged in successfully");
    } else {
        return back()->with('error', "You entered wrong info");
    }
    }

    public function logout()
    {
      Auth::logout();
      return to_route('login.page');

    }
}
