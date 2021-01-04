<?php

namespace App\Http\Controllers;

use App\Models\friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Users extends Controller
{

    /**
     * @param Request $request
     * @return array
     */
    public function getUsersAndFriendsByString(Request $request){

        $id = Auth::id();
        $string = $request->string;

        if($string){

            $users = User::getAllUsersByString($id,$string);
            $friends = friendship::getFriendsIds($id);

            return ['users'=>$users,'friends'=>$friends];
        }else{
            return ['users'=>[],'friends'=>[]];
        }

    }

}
