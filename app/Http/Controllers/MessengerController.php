<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    //
    public function index($id = null)
    {
        $user = Auth::user();
        $friends = User::where('id', '<>' , $user->id)
            ->orderBy('name')
            ->paginate();


        $conversations = $user->conversations()->with([
            'lastMessage',
            'participants' => function($builder) use ($user){
                $builder->where('id', '<>', $user->id);
            }
        ])->get();



        $messages = [];
        $activeChat = new Conversation();
        if($id){
            $activeChat = $conversations->where('id', $id)->first();
            $messages = $activeChat->messages()->with('user')->paginate();
        }


        return view('messenger.index',[
            'friends' => $friends,
            'conversations' => $conversations,
            'activeChat' => $activeChat,
            'messages' => $messages
        ]);
    }
}
