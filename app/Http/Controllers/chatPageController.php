<?php

namespace App\Http\Controllers;

use App\Models\message;
use App\Models\server;
use App\Models\user;
use Illuminate\Http\Request;

class chatPageController extends Controller
{
    public function showChatPage($serverid){
        $server = server::find($serverid);
        if($server){
            $server = $server::with('messages.user')->where("id",$serverid)->first();
            session()->put("serverid",$server->id);
            session()->put("owner",$server->user_id);
            return view("chatpage", ["serverData"=> $server]);
        }
        else{
            return view('overview');
        }
    }

    public function sendMessage(Request $request){
        try{
            message::create([
                "user_id"=>auth()->id(),
                "server_id"=>session()->get("serverid"),
                "content"=>$request->input("newchat")
            ]);    
        }
        catch(\Exception $e){
            session()->flash("messageSendError","Invalid message");
        }
        return redirect()->route("chatpage",["serverid"=>session()->get("serverid")]);
    }

    public function deleteMessage(Request $request){
        $message = message::find($request->input("messageid"));
        //check if the user is the owner of the message, or if its the owner of the server
        if($message){
            if($message->user_id == auth()->id() && $message->server_id == session()->get("serverid") || session()->get("owner") == auth()->id()){
                $message->delete();
            }
        }
        return redirect()->route("chatpage",["serverid"=>session()->get("serverid")]);
    }

    public function editMessage(Request $request){
        $message = message::find($request->input("messageid"));
        if($message){
            $message->content = $request->input("editmessage");
            $message->edited = true;
            $message->save();
        }
        return redirect()->route("chatpage",["serverid"=>session()->get("serverid")]);
    }

    public function inviteUser(Request $request){
        $invitedUserId = user::where("username",$request->input("username"))->get()->first();
        if(!$invitedUserId){
            session()->flash("addUserError","User does not exist");
            return redirect()->route("chatpage",["serverid"=>session()->get("serverid")]);
        }
        $invitedUserId = $invitedUserId->id;
        $alreadyAdded = user::whereHas("servers",function($query){
            $query->where("user_server.server_id",session()->get("serverid"));
        })->where("id",$invitedUserId)->exists();
        if(!$alreadyAdded){
            $user = user::find($invitedUserId);
            $user->servers()->attach(session()->get("serverid"));
            return redirect()->route("chatpage",["serverid"=>session()->get("serverid")]);
        }
        else{
            session()->flash("addUserError","User already added");
            return redirect()->route("chatpage",["serverid"=>session()->get("serverid")]);
        }
    }

    public function removeUser(Request $request){
        if(session()->get("owner") == auth()->id()){
            $user = user::find($request->userid);
            $user->servers()->detach(session()->get("serverid"));
        }
        return redirect()->route("chatpage",["serverid"=>session()->get("serverid")]);
    }
}