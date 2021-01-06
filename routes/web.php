<?php

use App\Events\Friends;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/users',[App\Http\Controllers\Users::class, 'getUsersAndFriendsByString'])->name('users');

Route::post('/add_friend',[App\Http\Controllers\UsersFriendsController::class, 'AddFriend']);

Route::post('/friends',[App\Http\Controllers\UsersFriendsController::class, 'FriendsList']);

Route::post('/remove_friend',[App\Http\Controllers\UsersFriendsController::class, 'RemoveFriend']);

Route::prefix('request')->group(function (){
    Route::post('/approve',[App\Http\Controllers\UsersFriendsController::class, 'ApproveFriendRequest']);
    Route::post('/rejected',[App\Http\Controllers\UsersFriendsController::class, 'RejectedFriendRequest']);
    Route::post('/list',[App\Http\Controllers\UsersFriendsController::class, 'FriendRequestList']);
});

