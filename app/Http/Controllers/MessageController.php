<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;


class MessageController extends Controller
{
    public function index(){
        $users = User::where('id','!=',Auth::id())->get();
        return view('users',compact('users'));
    }

    public function chat($receiverId){
        $receiver = User::find($receiverId);
        $messages = Message::where(function($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
              ->where('receiver_id', $receiverId);
        })->orWhere(function($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
              ->where('receiver_id', Auth::id());
        })->get();
        return view('chat',compact('receiver' , 'messages'));
    }

    public function sendMessage(Request $request , $receiverId){
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id'=>Auth::id(),
            'receiver_id'=>$receiverId,
            'content'=>$request->message
        ]);

        broadcast(new \App\Events\MessageEvent($message))->toOthers();

        return response()->json([
                'status' => 'success',
                'message' => [
                    'content' => $message->content,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                ],
            ]);    

    


}

   public function typing(Request $request){
    $typerId = Auth::id();

    broadcast(new \App\Events\UserTyping($typerId))->toOthers();

    return response()->json(['status'=>'success'],200);
}


    public function setOnline(Request $request){
        cache()->put('user-is-online-'.Auth::id(),true,now()->addMinutes(5));
        return response()->json(['status'=>'success'],200);
    }

    public function setOffline(Request $request){
        cache()->forget('user-is-online-'.Auth::id());
        return response()->json(['status'=>'success'],200);
    }



}
