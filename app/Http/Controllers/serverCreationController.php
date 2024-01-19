<?php

namespace App\Http\Controllers;
use App\Classes\InputValidation as iv;
use App\Models\server;
use App\Models\user;
use App\Models\message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class serverCreationController extends Controller
{
    public function createServer(Request $request){
        $servername = $request->input("servername");
        if($servername){
            //servercreation validation errors
            $errors = iv::validateServerCreation($servername);
            if($errors){
                session()->flash("error",$errors[0]);
                return redirect()->route("servercreation");
            }
            else{
                $currentUserId = auth()->id();
                try{
                    DB::beginTransaction();
                    $server = new server([
                        "name"=> $servername,
                        "user_id"=> $currentUserId
                    ]);
                    $server->save();
                    $user = user::find($currentUserId);
                    $user->servers()->attach($server->id);
                    DB::commit();
                }
                catch(\Exception $exeption){
                    DB::rollBack();
                    throw $exeption;
                }
                return redirect()->route("overview");
            }
        }
        else{
            session()->flash("error","Servername is invallid");
            return redirect()->route("servercreation");
        }
    }

    public function deleteServer(Request $request){
        if($request->input("confirmation") === "yes" ){
            $user = user::findOrFail(auth()->id());
            $server = server::findOrFail(session()->get("serverid"));
            if($server && $user){
                try{
                    DB::beginTransaction();
                    $messages = message::where("server_id",session()->get("serverid"))->get();
                    $user->servers()->detach($server->id);
                    foreach($messages as $message){
                        $message->delete();
                    }
                    $server->delete();
                    DB::commit();
                }
                catch(\Exception $exeption){
                    DB::rollBack();
                    throw $exeption;
                }
            }
            return redirect()->route("overview");
        }
        session()->flash("serverDeleteError", "something went wrong");
        return redirect()->route("chatpage");
    }
}
