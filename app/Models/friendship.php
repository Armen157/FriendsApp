<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class friendship extends Model
{
    use HasFactory;

    public $table = "friendship";
    public $timestamps = false;
    protected $primaryKey = 'friendship_id';

    protected $fillable = [
        'user_sender_id',
        'user_receiver_id',
        'application_send_datetime',
        'approve_datetime',
        'status_id'
    ];

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public static function getFriendsList($id){

        $your_applications = DB::table('friendship')
            ->select("friendship.user_sender_id","friendship.status_id","users.name","users.lastname")
            ->join('users', 'friendship.user_sender_id', '=', 'users.id')
            ->where('friendship.user_receiver_id',$id)->where(function($query) {
                $query->where('friendship.status_id', 1)->orWhere('friendship.status_id',2);
            });

        $general_applications = DB::table('friendship')
            ->select("friendship.user_receiver_id","friendship.status_id","users.name","users.lastname")
            ->join('users', 'friendship.user_receiver_id', '=', 'users.id')
            ->where('friendship.user_sender_id',$id)->where(function($query) {
                $query->where('friendship.status_id', 1)->orWhere('friendship.status_id',2);
            })->union($your_applications)->get();


        return $general_applications;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statuses()
    {
        return $this->belongsTo('App\models\statuses','status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\models\User','id');
    }

}
