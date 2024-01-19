<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Classes\InputValidation as IP;
use App\Classes\AccountInput;
use App\Models\user;
use Illuminate\Support\Facades\Hash;

class accountCreationController extends Controller
{
    public function newAccount(Request $request){
        $email = $request->input("email");
        $username = strtolower($request->input("username"));
        $password = $request->input("password");
        $passwordConfirm = $request->input("passwordConfirm");
        //account creation inputvalidation errors
        $errors = IP::validateAccountCreation(new AccountInput($username,$password,$passwordConfirm,$email));
        if(count($errors)==0){
            $password = Hash::make($password);
            user::create([
                "username"=>$username,
                "password"=>$password,
                "email"=>$email
            ]);
            return redirect()->route("login");
        }
        else{
            session()->flash("error",$errors[0]);
            return redirect()->route("accountcreation");
        }
    }
}
