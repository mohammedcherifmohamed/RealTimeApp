<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MessageController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/pass', function () {
    echo bcrypt('aze');

});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function(){
    Route::get('/users',[MessageController::class,'index'])->name('users');
    Route::get('/chat/{receiverId}',[MessageController::class,'chat'])->name('chat');
    Route::post('/chat/{receiverId}/send',[MessageController::class,'sendMessage'])->name('chat.send');
    Route::post('/chat/typing',[MessageController::class,'typing'])->name('chat.typing');
    Route::post('/online',[MessageController::class,'setOnline'])->name('chat.online');
    Route::post('/offline',[MessageController::class,'setOffline'])->name('chat.offline');


});