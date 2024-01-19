<?php

namespace App\Http\Controllers;

use App\Classes\AccountInput;
use App\Classes\InputValidation as IP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function login(Request $post){
        $username = $post->input("username"); 
        $password = $post->input("password");
        $failedLoginMessage = "This username does not exist or you used the wrong password";
        if($username && $password){
            $errors = IP::validateLogin(new AccountInput($username,$password));
            if($errors){
                session()->flash("error",$failedLoginMessage);
                return redirect()->route("login");
            }
            else if(Auth::attempt(["username"=>$username,"password"=>$password])){
                session()->put("username", $username);
                return redirect()->route("overview");
            }
        }
        session()->flash("error",$failedLoginMessage);
        return redirect()->route("login");
    }
}
