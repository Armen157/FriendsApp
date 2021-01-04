<?php

namespace App\Http\Controllers;

use App\Models\friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersFriendsController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function AddFriend(Request $request){

        $id = Auth::id();
        $user_receiver_id = $request->user_receiver_id;

        friendship::create([
            'user_sender_id' => $id,
            'user_receiver_id'=>$user_receiver_id,
            'status_id'=>2
        ]);

        return redirect()->back()->with('message', 'Success');
    }
}
