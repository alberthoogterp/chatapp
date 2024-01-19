<?php

use App\Http\Controllers\loginController;
use App\Http\Controllers\accountCreationController;
use App\Http\Controllers\chatPageController;
use App\Http\Controllers\overviewController;
use App\Http\Controllers\serverCreationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/{slug?}', function(){
    return view('login');
})->where('slug', 'login')->name("login");
Route::post('/{slug?}', [loginController::class, 'login'])->where("slug","login");
Route::get('/accountcreation', function(){
    return view('accountcreation');
})->name("accountcreation");
Route::post('/accountcreation', [accountCreationController::class, 'newAccount']);

Route::middleware(["auth"])->group(function(){
    Route::get('/overview', [overviewController::class, "showOverview"])->name("overview");
    Route::post("/overview", [overviewController::class, "logout"])->name("logout");
    Route::get("/servercreation", function(){
        return view("servercreation");
    })->name("servercreation");
    Route::post("/servercreation", [serverCreationController::class, "createServer"])->name("createServer");
    Route::post("/servercreation/deleteServer",[serverCreationController::class, "deleteServer"])->name("deleteServer");
    Route::get('/chatpage/{serverid}', [chatPageController::class, "showChatPage"])->name("chatpage");
    Route::post("/chatpage/send", [chatPageController::class, "sendMessage"])->name("sendMessage");
    Route::post("/chatpage/delete", [chatPageController::class, "deleteMessage"])->name("deleteMessage");
    route::post("/chatpage/edit", [chatPageController::class, "editMessage"])->name("editMessage");
    Route::post("/chatpage/inviteUser", [chatPageController::class, "inviteUser"])->name("inviteUser");
    Route::post("/chatpage/removeUser", [chatPageController::class, "removeUser"])->name("removeUser");
});