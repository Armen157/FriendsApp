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

    /**
     * @param Request $request
     * @return bool
     */
    public static function RemoveFriend(Request $request){


        friendship::where(function($query) {
            $id = Auth::id();
            $query->where('user_sender_id', $id)->orWhere('user_receiver_id',$id);
        })->where(function($query) {
            global $request;
            $user_receiver_id=$request->user_id;
            $query->where('user_sender_id', $user_receiver_id)->orWhere('user_receiver_id',$user_receiver_id);
        })->delete();

        return true;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function FriendsList(){

        $id = Auth::id();
        $friends = friendship::getFriendsList($id);
        return $friends;

    }
}
