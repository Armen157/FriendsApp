<?php

namespace App\Http\Controllers;

use App\Events\Friends;
use App\Models\friendship;
use Carbon\Carbon;
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

        //friendship create
        $data = friendship::create([
            'user_sender_id' => $id,
            'user_receiver_id'=>$user_receiver_id,
            'status_id'=>2
        ]);

        $data->save();
        $friendship_id =$data->friendship_id;

        //get user
        $user = Users::getUser($id);

         //get name lastname
         $name = $user['name'];
         $lastname = $user['lastname'];

         // event pusher
         event(new Friends($friendship_id,$user_receiver_id,$name,$lastname));

        return redirect()->back()->with('message', 'Success');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public static function RemoveFriend(Request $request){

        $id = Auth::id();
        $user_receiver_id=$request->user_id;

        friendship::where(function($query) use ($id){
            $query->where('user_sender_id', $id)->orWhere('user_receiver_id',$id);
        })->where(function($query) use ($user_receiver_id) {
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

    /**
     * @param Request $request
     * @return bool
     */
    public function ApproveFriendRequest(Request $request){

        $friendship_id = $request->friendship_id;
        friendship::where('friendship_id', $friendship_id)->update(["status_id"=>1,"approve_datetime"=>Carbon::now()]);
        return True;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function RejectedFriendRequest(Request $request){

        $friendship_id = $request->friendship_id;
        friendship::where('friendship_id', $friendship_id)->update(["status_id"=>3]);
        return True;
    }

    /**
     * @return array
     */
    public function FriendRequestList(){

        $user_receiver_id = Auth::id();
        $list = friendship::select("friendship.friendship_id","users.name","users.lastname")
                    ->join('users', 'friendship.user_sender_id', '=', 'users.id')
                    ->where('friendship.user_receiver_id', $user_receiver_id)
                    ->where('friendship.status_id',2)
                     ->get();

        if($list){
            return $list;
        }else{
            return [];
        }
    }

}
