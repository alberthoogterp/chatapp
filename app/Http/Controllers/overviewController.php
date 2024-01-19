<?php

namespace App\Http\Controllers;

use App\Models\server;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class overviewController extends Controller
{
    public function showOverview(){
        $serverList = Server::whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();
        return view("overview", ["serverList"=> $serverList]);
    }

    public function logout(){
        Auth::logout();
        Session::flush();
        return redirect("login");
    }
}
